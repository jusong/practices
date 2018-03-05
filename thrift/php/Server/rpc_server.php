<?php
header('Content-Type', 'application/x-thrift');

require_once __DIR__ . '/../Common/common.php';
require_once __DIR__ . '/../Fasthand/Handler/OrderServiceHandler.php';

use Thrift\TMultiplexedProcessor;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferSocket;
use Thrift\Transport\TFramedTransport;
use Thrift\Exception\TException;

class server {
	private $_host = "0.0.0.0";
	private $_port = 9511;
	private $serv;
	public function __construct($host = "0.0.0.0", $port = 9511) {
		$this->_host = $host;
		$this->_port = $port;
	}
	public function run() {
		$this->serv = new swoole_server($this->_host, $this->_port);	
		$this->serv->set(array(
			"worker_num" => 8, //事件处理进程数
			"task_worker_num" => 8, //任务处理进程数
			"task_max_request" => 10000,//任务进程最大处理请求次数
			"task_worker_max" => 10000,//任务处理进程最大数
			"daemonize" => false,//守护进程
			"max_request" => 10000,//事件处理进程最大处理请求次数
			"dispatch_mode" => 2,//数据包分发策略：固定分发
			"open_length_check" => true,//自定义协议：固定长度
			"package_length_type" => 'N',//长度字节类型
			"package_max_length" => 102400,//数据包最大长度
		    'package_length_offset' => 0,//长度字节偏移量
		    'package_body_offset' => 4,//长度所占字节大小
		));
		$this->serv->on("Start", array($this, "onStart"));
		$this->serv->on("Receive", array($this, "onReceive"));
		$this->serv->on("Task", array($this, "onTask"));
		$this->serv->on("Finish", array($this, "onFinish"));
		$this->serv->start();
	}
	public function onStart(swoole_server $serv) {
		echo "Swoole server start on 9511 ...\n";
	}
	public function onReceive(swoole_server $serv, $fd, $from_id, $data) {
		$task_data = array("fd" => $fd, "data" => $data);
		$serv->task($task_data);
	}
	public function onTask(swoole_server $serv, $task_id, $from_id, $task_data) {
		$data = $task_data['data'];

		$orderServiceProcessor = new \Fasthand\Service\Order\OrderServiceProcessor(new \Fasthand\Handler\OrderServiceHandler());

		$processor = new TMultiplexedProcessor();
		$processor->registerProcessor("OrderService", $orderServiceProcessor);
	
		$transport = new TFramedTransport(new TBufferSocket($data));
		$protocol = new TBinaryProtocol($transport, true, true);

		$transport->open();
		$processor->process($protocol, $protocol);
		$out_data = $transport->getOutput();
		$transport->close();
		$task_data['data'] = $out_data;
		return $task_data;
	}
	public function onFinish(swoole_server $serv, $task_id, $data) {
		$serv->send($data['fd'], $data['data']);
	}
}
$server = new Server();
$server->run();
?>
