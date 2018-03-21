<?php
/*****************************************************************
 * 文件名称：test_fopen.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-12 17:04
 * 描    述：
 *****************************************************************/

$fp = fopen("/tmp/log", "w");
fwrite($fp, "fp hello\n");
$bfp = $fp;
fclose($fp);
fwrite($bfp, "bfp hello\n");
fclose($bfp);
