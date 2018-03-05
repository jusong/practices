<?php
namespace Fasthand\php;

require_once 'common.php';
require_once 'connect.php';
require_once 'config/autoload.php';

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

try {

$calculatorClient = new \tutorial\CalculatorClient(new \Thrift\Protocol\TMultiplexedProtocol($protocol, "Calculator"));
$fasthandServiceClient = new \Fasthand\FasthandServiceClient(new \Thrift\Protocol\TMultiplexedProtocol($protocol, "FasthandService"));

// Connect
$transport->open();

$userId = "550";
$userInfo = $fasthandServiceClient->getUserInfo($userId);
var_dump($userInfo);

$paramArray = array(
	"userId" => 1000,
	"nick" => "jia",
	"sex" => "1",
	"age" => "12",
	"school" => "tianjinkejidaxue",
	"city" => "beijing",
	"role" => "parent",
);
$result = $fasthandServiceClient->upFirstChar($paramArray);
var_dump($result);

$time = $fasthandServiceClient->getTime();
echo $time."\n";
$areaInfoArray = $fasthandServiceClient->getAreaInfo();
foreach($areaInfoArray as $areaInfo) {
	echo  $areaInfo->type . "	" . $areaInfo->code . "	" . $areaInfo->p_code . "	" . $areaInfo->name . "\n";
}

$areaInfo = $fasthandServiceClient->getAreaNameList();
var_dump($areaInfo);

$time = $fasthandServiceClient->getTime();
echo $time."\n";

$res = $calculatorClient->add(2, 5);
echo "2 + 5 = $res\n";

// Close
$transport->close();
} catch (TException $tx) {
	//var_dump($tx);
	echo 'TException: '.$tx->getMessage()."\n";
}
