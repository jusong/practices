<?php
require_once __DIR__ . '/../Common/common.php';

use Thrift\Transport\TSocket;
use Thrift\Transport\TFramedTransport;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TCompactProtocol;

define("SERVER_HOST", "localhost");
//define("SERVER_HOST", "121.199.42.40");
define("SERVER_PORT", 9511);

// Init 
$socket = new TSocket(SERVER_HOST, SERVER_PORT);
$socket->setSendTimeout(200);
$socket->setRecvTimeout(3000);
$transport = new TFramedTransport($socket);
$protocol = new TBinaryProtocol($transport, true, true);
//$protocol = new TCompactProtocol($transport, true, true);
?>
