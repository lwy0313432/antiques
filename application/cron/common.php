<?php
/**
 * Yindou Framework
 * (C)2015-2020 Yindou Inc. (cfca.yindou.cn)
 *
 * Cron脚本公共头文件
 *
 * @Package Yindou Index
 * @version $Id: common.php 1092 2015-09-29 02:01:03Z wuwenjia $
 **/

//初始化时区
date_default_timezone_set('Asia/Shanghai');
define("DS",  DIRECTORY_SEPARATOR);
define("APP_PATH",  realpath(dirname(__FILE__) . '/../../')); //指向public的上一级

$application  = new Yaf_Application(APP_PATH . DS . 'conf' . DS . 'index.ini');
//yaf官网命令行路由 暂时停用
//$application->getDispatcher()->dispatch(new Yaf_Request_Simple("CLI", "Weixin", "Cron_{$_controller}", $_action, array("action" => $_param)));
$application->bootstrap();

//只能在命令行下运行
if(php_sapi_name() != "cli"){
	Data_LogModel::red('[cron] run not in cli', $_SERVER['argv']);
	die(date('Y-m-d H:i:s').'['.implode(' ', $_SERVER['argv']).'] run not in cli');
}

//接收参数
$action = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : null;

//保证脚本同一时间只运行一次
$cache_key = md5($_SERVER['argv'][0].'_'.$action);
if(!Common_Fileflag::Factory($cache_key)->lock()){
	//Data_LogModel::notice('[cron] already runnning', $_SERVER['argv']);
	die(date('Y-m-d H:i:s')." {$_SERVER['argv'][0]} already runnning \n");
}
