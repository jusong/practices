<?php
/*****************************************************************
 * 文件名称：test_xml.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-01-09 10:55
 * 描    述：
 *****************************************************************/

$string = <<<XML
<document>
  <cmd>login</cmd>
  <login>imdonkey</login>
</document>
XML;

$string = <<<XML
<request><header><signed>sdasdfs234234sdf</signed></header><body><order><orderId>10000</orderId><status>NORMAL</status><approveStatus>true</approveStatus><paymentStatus>UNPAY</paymentStatus><waitPaymentTime></waitPaymentTime><credenctStatus>CREDENCE_NO_SEND</credenctStatus><performStatus>USED</performStatus></order></body></request>
XML;
var_dump(simplexml_load_string($string));
$xml = json_decode(json_encode(simplexml_load_string($string)), true);
var_dump($xml);
