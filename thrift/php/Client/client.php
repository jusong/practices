<?php
namespace phpClient;

require_once __DIR__ . '/../Common/common.php';
require_once 'connect.php';

use Thrift\Exception\TException;
use Thrift\Protocol\TMultiplexedProtocol;

use Fasthand\Service\Order;

try {
	$orderService = new Order\OrderServiceClient(new TMultiplexedProtocol($protocol, "OrderService"));
	
	//打开链接 
	$transport->open();
	
	$fasthand_order = array("id" => 34, "user_id" => 45, "create_time" => "2016-12-23 12:00:00");
	//$orderService->addOrderHistory($fasthand_order);
	//$orderService->sendNotPaySms(358142);
	//$integral = $orderService->getOrderIntegralNum(358142);
	//echo $integral . "\n";
	$request = array(
		"type" => "activity",
		"event_id" => "70",
		"sku_id" => "132",
		"package_num" => "2",
		"number" => "3",
		"contacts" => "贾仿栋",
		"mobile" => "18210672147",
	);
	//$res = $orderService->createItemSnapshotObject($request, $seller_id, $seller_user_id);
	$res = $orderService->mGetCoursesInfoById(316);
	if (is_array($res)) {
		echo json_encode($res) . "\n";
	} else {
		echo $res . "\n";
	}

	//关闭链接
	$transport->close();
} catch (TException $tx) {
	//var_dump($tx);
	echo 'TException: '.$tx->getMessage()."\n";
}
