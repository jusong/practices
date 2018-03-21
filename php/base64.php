<?php
/*****************************************************************
 * 文件名称：base64.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-06 09:38
 * 描    述：
 *****************************************************************/

$file = '/home/juson/Pictures/qrcodes/115197887857183.png';
print base64_encode(file_get_contents($file)).PHP_EOL;
