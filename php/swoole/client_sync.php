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
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
        $this->client->set(array(
            'open_length_check'     => 1,
            'package_length_type'   => 'N',
            'package_max_length'    => 102400,  //协议最大长度
            'package_length_offset' => 0,
            'package_body_offset' => 4,
        ));
        $this->client->connect($this->_host, $this->_port, 1, 0);
    }
    public function write($data) {
        $msg = pack("N", strlen($data)). $data;
        $this->client->send($msg);
    }
    public function read($length) {
        $data = $this->client->recv($length);
        $dataArray = unpack("N", $data);
        var_dump($dataArray);
        $length = $dataArray[1];
        $data = $length . substr($data, -$length);
        return $data;
    }
}

$client = new Client("127.0.0.1");
$client->write(json_encode(array('type' => 'name', 'data' => 'tom')));
$client->write(json_encode(array('type' => 'msg', 'data' => array('to' => 'any', 'msg' => 'hello'))));
sleep(20);
