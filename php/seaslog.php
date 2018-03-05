<?php
$basePath1 = Seaslog::getBasePath();
SeasLog::setBasePath("/tmp/seaslog.txt");
$basePath2 = SeasLog::getBasePath();
var_dump($basePath1, $basePath2);

$lastLogger1 = SeasLog::getLastLogger();
SeasLog::setLogger("test/log");
$lastLogger2 = SeasLog::getLastLogger();
var_dump($lastLogger1, $lastLogger2);

echo SEASLOG_DEBUG.PHP_EOL;
exit;
SeasLog::log(SEASLOG_DEBUG, 'This is a {userName} debug',array('{userName}' => 'neeke'));
SeasLog::debug('this is a {userName} debug',array('{userName}' => 'neeke'));
SeasLog::info('this is a info log');
SeasLog::notice('this is a notice log');
SeasLog::warning('your {website} was down,please {action} it ASAP!',array('{website}' => 'github.com','{action}' => 'rboot'));
SeasLog::error('a error log');
SeasLog::critical('some thing was critical');
SeasLog::alert('yes this is a {messageName}',array('{messageName}' => 'alertMSG'));
SeasLog::emergency('Just now, the house next door was completely burnt out! {note}',array('{note}' => 'it`s a joke'));


$countResult_1 = SeasLog::analyzerCount();
$countResult_2 = SeasLog::analyzerCount(SEASLOG_WARNING);
$countResult_3 = SeasLog::analyzerCount(SEASLOG_ERROR,date('Ymd',time()));
var_dump($countResult_1, $countResult_2, $countResult_3);

$detailErrorArray_inAll = SeasLog::analyzerDetail(SEASLOG_ERROR);
$detailErrorArray_today = SeasLog::analyzerDetail(SEASLOG_ERROR,date('YmdH', time() - 3600));
var_dump($detailErrorArray_inAll, $detailErrorArray_today);
