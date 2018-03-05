<?php
class server {

    private $_host = "localhost";
    private $_port = 9511;
    private $serv;

    public function __construct($host = "localhost", $port = 9511) {
        $this->_host = $host;
        $this->_port = $port;
    }

    public function run() {

        $this->serv = new swoole_server($this->_host, $this->_port);	
        $this->serv->set(array(
            "worker_num" => 2,
            "task_worker_num" => 2,
            "task_max_request" => 10000,
            //				"daemonize" => true,
            "max_request" => 10000,
            "dispatch_mode" => 2,
            "debug_mode" => 1,
            "open_length_check" => 1,
            "package_length_type" => 'N',
            "package_max_length" => 102400,
            'package_length_offset' => 0,
            'package_body_offset' => 4,
            'heartbeat_check_interval'  => 5,
            'heartbeat_idle_time' => 10
        ));

        $this->serv->on("Start", array($this, "onStart"));
        $this->serv->on("Shutdown", array($this, "onShutdown"));
        //$this->serv->on("WorkerStart", array($this, "onWorkerStart"));
        $this->serv->on("Connect", array($this, "onConnect"));
        $this->serv->on("Receive", array($this, "onReceive"));
        $this->serv->on("Close", array($this, "onClose"));
        //$this->serv->on("ManagerStart", array($this, "onManagerStart"));
        //$this->serv->on("ManagerStop", array($this, "onManagerStop"));
        $this->serv->on("Task", array($this, "onTask"));
        $this->serv->on("Finish", array($this, "onFinish"));

        $table = new swoole_table(1024);
        $table->column('fd', swoole_table::TYPE_INT);
        $table->column('from_id', swoole_table::TYPE_INT);
        $table->column('data', swoole_table::TYPE_STRING, 64);
        $table->create();
        $this->serv->table = $table;
        $this->serv->start();
    }

    public function onStart(swoole_server $serv) {
        //echo "****** onStart: \n";
        //echo "master_srv_pid: " . $serv->master_pid . "\n";
        //echo "manager_pid: " . $serv->manager_pid . "\n\n";
    }

    public function onShutdown(swoole_server $serv) {
        //echo "****** onShutdown: \n";
        //echo "master_srv_pid: " . $serv->master_pid . "\n";
        //echo "manager_pid: " . $serv->manager_pid . "\n";
        //echo "worker_id: " . $serv->worker_id . ", worker_pid: " . $serv->worker_pid . "\n\n"; 
    }
    public function onWorkerStart(swoole_server $serv, $worker_id) {
        //echo "****** onWorkerStart: \n";
        if ($worker_id >= $serv->setting['worker_num']) {
            //echo "task worker {$worker_id}\n";
            //swoole_set_prcess_name("task worker {$worker_id}");
            //cli_set_process_title("task worker {$worker_id}");
        } else {
            //echo "event worker {$worker_id}\n";
            //cli_set_process_title("task worker {$worker_id}");
        }
        //echo "\n";
    }
    public function onConnect(swoole_server $serv, $fd, $from_id) {
        echo "****** onConnect: \n";
        echo "worker_id: " . $serv->worker_id . ", fd: " . $fd . ", from_id: " . $from_id . "\n\n";
    }
    public function onReceive(swoole_server $serv, $fd, $from_id, $data) {
        $dataArray = unpack('N', $data);
        $msg = substr($data, -$dataArray[1]);
        $data = json_decode($msg, 1);
        if ('name' == $data['type']) {
            echo 'set name: '.$data['data'].PHP_EOL;
            $serv->table->set($data['data'], array('from_id' => $from_id, 'fd' => $fd));
        } else {
            $task_data = array("fd" => $fd, "data" => $data['data']);
            $serv->task($task_data);
        }
    }
    public function onClose(swoole_server $serv, $fd, $from_id) {
        echo "****** onClose: \n";
        echo "worker_id: " . $serv->worker_id . ", fd: " . $fd . ", from_id: " . $from_id . "\n\n";
    }
    public function onTask(swoole_server $serv, $task_id, $from_id, $data) {
        var_dump($data);
        $to = $data['data']['to'];
        $msg = $data['data']['msg'];
        $toData = $serv->table->get($to);
        var_dump($toData);
        $fd = $toData['fd'];
        $serv->send($fd, pack('N', strlen($msg)).$msg);
    }
    public function onFinish(swoole_server $serv, $task_id, $data) {
    }
}

$server = new Server();
$server->run();
