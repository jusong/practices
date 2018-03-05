<?php
class Client {
    private $_host;
    private $_port;
    private $client;

    public function __construct($host = "localhost", $port = 9511) {
        $this->_host = $host;
        $this->_port = $port;
        $this->connect();
    }
    private function connect() {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
        $this->client->set(array(
            'open_length_check'     => 1,
            'package_length_type'   => 'N',
            'package_max_length'    => 102400,  //协议最大长度
            'package_length_offset' => 0,
            'package_body_offset' => 4,
        ));
        $this->client->on("connect", array($this, "onConnect"));
        $this->client->on("receive", array($this, "onReceive"));
        $this->client->on("error", array($this, "onError"));
        $this->client->on("close", array($this, "onClose"));
        $this->client->connect($this->_host, $this->_port, 1, 0);
    }
    public function onConnect(swoole_client $client) {
        echo "****** onConnect: \n\n";	
        echo " This is tom".PHP_EOL;
        $this->write(json_encode(array('type' => 'name', 'data' => 'tom')));
        $this->write(json_encode(array('type' => 'msg', 'data' => array('to' => 'any', 'msg' => 'hello'))));
    }
    public function onReceive(swoole_client $client, $data) {
        echo "****** onReceive: \n";
        $dataArray = unpack("N", $data);
        $length = $dataArray[1];
        $data = $length . substr($data, -$length);
        echo "data: {$data}\n\n";
    }
    public function onError(swoole_client $client) {
        echo "***** onError: \n\n";
    }
    public function onClose(swoole_client $client) {
        echo "***** onClose: \n\n";
    }
    public function write($data) {
        $msg = pack("N", strlen($data)). $data;
        $this->client->send($msg);
    }
    public function read($length) {
            /*
            $data = $this->client->recv($length);
            $dataArray = unpack("N", $data);
            $length = $dataArray[1];
            $data = $length . substr($data, -$length);
            return $data;
             */

        $readArray = array($this->client);
        $writeArray = array();
        $errorArray = array();
        $count = swoole_client_select($readArray, $writeArray, $errorArray, 1);
        foreach($readArray as $client) {
            $data = $client->recv($length);
        }
        $dataArray = unpack("N", $data);
        $length = $dataArray[1];
        $data = $length . substr($data, -$length);
        return $data;
    }
}
$client = new Client("127.0.0.1");
