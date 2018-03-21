<?php
/*****************************************************************
 * 文件名称：test_pack.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-07 11:02
 * 描    述：
 *****************************************************************/

$itemId = '23254';
$myCardId = '4566';
$yuyueTag = '2';
$etype = '1';
$expireTime = time();

$playload = pack('I2C2I', $myCardId, $itemId, $etype, $yuyueTag, $expireTime);
$playloadLen = strlen($playload);
$header = pack('C3S', 1, 1, 1, $playloadLen);
$data = base64_encode($header.$playload);
var_dump($data, strlen($header.$playload), strlen($data));

//$data = base64_decode($data);
$header = unpack('Cprogrem/Ctype/Cversion/Splayloadlen', $data);
$playload = substr($data, 5);
$playload = unpack('ImyCardId/IitemId/Cetype/CyuyueTag/IexpireTime', $playload);
var_dump($header, $playload);


$data = pack('C2SI', 1, 2, 234, 2234);
$len = unpack('Slen', substr($data, 2, 2));
var_dump($len);

$str = 'AQIBDQB2+wAAgwkAAAH9uJ9a';
$data = unpack('C3header/Slen/Imycardid/Iitemid/Cetype/Iexp', base64_decode($str));
var_dump($data);

$str = '{"itemId":"x","myCardId":"x","yuyue_tag":"x","event_type":"x","qrsign":"eyJ0eXBlIjoiSldUIiwiYWxnIjoiU0hBMjU2In0=.eyJpc3MiOiJlZHUtY2hpbmEuY29tIiwic3ViIjoiY2FyZGl0ZW1fcnN2X3RpY2tldCIsImlhdCI6MTUyMDQ5NTMxMywiZXhwIjoxNTIwNDk1MzQ1LCJqdGkiOiJjYXJkaXRlbV9yc3ZfdGlja2V0NWFhMGVhZDFmMWE1YTYuOTEwODg2MTYiLCJkYXRhIjoiQVFFQkRRQjIrd0FBc2drQUFBSHg2cUJhIn0=.3e765f0dc512667a07eb1ff3431a34d1da88148ca21ac05ba728531bca42bf59"}';
$arr = json_decode($str, true);
$arr['qrsign'] = urldecode('eyJ0eXBlIjoiSldUIiwiYWxnIjoiU0hBMjU2In0%3D.eyJpc3MiOiJlZHUtY2hpbmEuY29tIiwic3ViIjoiY2FyZGl0ZW1fcnN2X3RpY2tldCIsImlhdCI6MTUyMDQ5NTUxNywiZXhwIjoxNTIwNDk1NTQ5LCJqdGkiOiJjYXJkaXRlbV9yc3ZfdGlja2V0NWFhMGViOWQwZDdlODAuOTUxNDgyNTYiLCJkYXRhIjoiQVFFQkRRQjQrd0FBdFFrQUFBRzg2NkJhIn0%3D.64ab32dfecb2656a2071de6f71af5522ef4280efedd28eb088ede09dd6af24e9');
$qrsignArr = explode('.', $arr['qrsign']);
$playload = json_decode(base64_decode($qrsignArr[1]), true);
var_dump(json_decode(base64_decode($qrsignArr[0]), true), $playload);
$data = base64_decode($playload['data']);

$header = unpack('C3header', $data);
var_dump($header);
$playloadLen = unpack('Slen', substr($data, 3, 2));
$data = unpack('Imycardid/Iitemid/Cetype/Iexp', substr($data, 5, $playloadLen['len']));
var_dump($data);

$str = 'eyJkYXRhIjoiQVFFQkRRQWx5QUVBWENnQUFBRVdHN05hIn0=';
$str = json_decode(base64_decode($str), true);
var_dump($str);
$data = base64_decode($str['data']);
$header = unpack('C3header', $data);
var_dump($header);
$playloadLen = unpack('Slen', substr($data, 3, 2));
$data = unpack('Imycardid/Iitemid/Cetype/Iexp', substr($data, 5, $playloadLen['len']));
var_dump($data);
