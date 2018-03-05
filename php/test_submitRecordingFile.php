<?php
/*****************************************************************
 * 文件名称：test_submitRecordingFile.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-02-08 11:27
 * 描    述：
 *****************************************************************/

$url = "http://api.pati.shenzhoujiajiao.net/ptRead/submitRecordingFile?apiVersion=1.2.0&clientSource=iPhone&clientVersion=1.2.0&digest=932c035f6eed790709d30d22056d4574&idfaStr=300F3279-5681-4673-A3D4-F73D83A7977C&pageNum=1&pageSize=20&screen_height=568&screen_width=320&sid=fdeg49ih52hk1rdv0ootui1qt1&status=1&t=510737348.744035&token=DAA4E57E-07EA-4F7D-BB67-9CAAEE676437&userId=358142&readtextId=1&beginTime=0&endTime=30&isOpen=1&homeworkId=81&worksType=2&score=50";

$source = '/home/blacknc/下载/381473322941.mp3';

$curl = curl_init();

if (class_exists('\CURLFile')) {// 这里用特性检测判断php版本
    curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
    $data = array('fileData' => new \CURLFile(realpath($source)));//>=5.5
} else {
    if (defined('CURLOPT_SAFE_UPLOAD')) {
        curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
    }
    $data = array('fileData' => '@' . realpath($source));//<=5.5
}

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, 1 );
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_USERAGENT,"TEST");
$output = curl_exec($curl);
echo $output.PHP_EOL;
