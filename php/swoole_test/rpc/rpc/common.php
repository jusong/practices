<?php
error_reporting(NULL);

require_once '/usr/lib/php/Thrift/ClassLoader/ThriftClassLoader.php';
use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath(dirname(__FILE__)).'/gen-php';

// Load
$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', '/usr/lib/php');
$loader->registerDefinition('shared', $GEN_DIR);
$loader->registerDefinition('Fasthand', $GEN_DIR);
$loader->registerDefinition('tutorial', $GEN_DIR);
$loader->register();
?>
