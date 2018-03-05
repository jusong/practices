<?php
namespace Fasthand\php;

require_once 'common.php';
require_once 'connect.php';

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

$fasthandServiceClient = new \Fasthand\FasthandServiceClient(new \Thrift\Protocol\TMultiplexedProtocol($protocol, "FasthandService"));
$calculatorClient = new \tutorial\CalculatorClient(new \Thrift\Protocol\TMultiplexedProtocol($protocol, "Calculator"));
try {
// Connect
$transport->open();

$userId = "550";
$userInfo = $fasthandServiceClient->getUserInfo($userId);
var_dump($userInfo);

$areaInfo = $fasthandServiceClient->getAreaInfo();
var_dump($areaInfo);

$areaInfo = $fasthandServiceClient->getAreaNameList();
var_dump($areaInfo);

$time = $fasthandServiceClient->getTime();
echo $time."\n";

$res = $calculatorClient->add(2, 5);
echo "2 + 5 = $res\n";

// Close
$transport->close();
} catch (TException $tx) {
	echo 'TException: '.$tx->getMessage()."\n";
}
