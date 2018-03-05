<?php
namespace Fasthand\php;

require_once 'common.php';
require_once 'FasthandServiceHandler.php';
require_once 'CalculatorServiceHandler.php';

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

header('Content-Type', 'application/x-thrift');

$fasthandServiceProcessor = new \Fasthand\FasthandServiceProcessor(new \Fasthand\php\FasthandServiceHandler());
$calculatorProcessor = new \tutorial\CalculatorProcessor(new \tutorial\php\CalculatorHandler());

$processor = new \Thrift\TMultiplexedProcessor();
$processor->registerProcessor("FasthandService", $fasthandServiceProcessor);
$processor->registerProcessor("Calculator", $calculatorProcessor);

$transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
$protocol = new TBinaryProtocol($transport, true, true);

$transport->open();
$processor->process($protocol, $protocol);
$transport->close();
?>
