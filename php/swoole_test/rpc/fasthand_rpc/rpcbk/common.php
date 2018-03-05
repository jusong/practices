<?php
error_reporting(E_ALL);
//error_reporting(NULL);
ini_set("display_errors", "Off");

require_once '/usr/lib/php/Thrift/ClassLoader/ThriftClassLoader.php';
use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath(dirname(__FILE__)) . '/gen-php';

// Load
$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', '/usr/lib/php');
$loader->registerDefinition('Fasthand', $GEN_DIR);
$loader->register();
