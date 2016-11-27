<?php
/**
 * Yindou Framework
 * (C)2015-2020 Yindou Inc. (http://www.yindou.com)
 *
 * yaf 入口文件ha   gsq
 *
 * @Package Yindou Index
 * @version $Id$
**/
define("APP_PATH",  realpath(dirname(__FILE__) . '/../../')); //指向public的上一级
define("DS",  DIRECTORY_SEPARATOR);

require_once APP_PATH . DS . "application" . DS . "library" . DS . "Common" . DS . "Tools.php";

//初始化时区
date_default_timezone_set('Asia/Shanghai');

$application  = new Yaf_Application(APP_PATH . DS . 'conf' . DS . 'index.ini');
 
$response = $application
	->bootstrap() //bootstrap是可选的调用
	->run(); //执行
