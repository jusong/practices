<?php
namespace phpClient;

require_once 'common.php';
require_once 'connect.php';

use Thrift\Exception\TException;
use Thrift\Protocol\TMultiplexedProtocol;

use Fasthand\Service\User;
use Fasthand\Service\Article;

try {
	$userClient = new User\FasthandUserServiceClient(new TMultiplexedProtocol($protocol, "FasthandUserService"));
	$articleClient = new Article\FasthandArticleServiceClient(new TMultiplexedProtocol($protocol, "FasthandArticleService"));
	
	//打开链接 
	$transport->open();
	
	//user测试
	$userInfo = array(
		"id" => 1,
		"username" => "jiafangdong",
		"nick" => "jiafd",
		"password" => "password123456",
		"age" => 25,
		"extendInfo" => "其他信息",
	);
	$userClient->storeUser(new User\FasthandUser($userInfo));

	$fasthandUser = $userClient->retrieveUserById(23);
	var_dump($fasthandUser);

	$userClient->deleteUserById(23);
	
	//article测试
	$articleInfo = array(
		"id" => 1,
		"uid" => 2,
		"title" => "大话西游",
		"content" => "爱你亿万年！",
		"create_time" => date("Y-m-d H:i:s"),
	);
	$articleClient->storeArticle(new Article\FasthandArticle($articleInfo));

	$articleList = $articleClient->retrieveArticleByUid(23);
	var_dump($articleList);

	$uidArray = array(1, 2, 4);
	$userArticleList = $articleClient->retrieveNewArticleList($uidArray);
	var_dump($userArticleList);

	
	//关闭链接
	$transport->close();
} catch (TException $tx) {
	//var_dump($tx);
	echo 'TException: '.$tx->getMessage()."\n";
}
