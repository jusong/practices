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
        $this->client->connect($this->_host, $this->_port, 1, 0);
    }
    public function write($data) {
        //$data = pack("N", strlen($data)). $data;
        $this->client->send($data);
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
$client->write(str_repeat('A', 10));
$client->write(str_repeat('B', 10)."\r\n");
$client->write(str_repeat('C', 10));

sleep(3);
