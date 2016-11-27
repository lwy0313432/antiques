<?php
/**
 * Yindou Framework
 * (C)2015-2020 Yindou Inc. (http://cfca..yindou.cn)
 *
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 *
 * @Package Yindou Index
 * @version $Id: Bootstrap.php 977 2015-09-21 09:33:49Z wuwenjia $
**/

class Bootstrap extends Yaf_Bootstrap_Abstract
{

	//初始化session
	public function _initSession()
	{
		if(php_sapi_name() == "cli") return false; //命令行下不执行
		Yaf_Session::getInstance()->start();
	}

	//初始化config ../conf/yindou.ini
	public function _initConfig()
	{
		
		$config = Yaf_Application::app()->getConfig();
		Yaf_Registry::set("config", $config); //往全局注册表添加一个新的项
	}

	//注册本地类前缀
	public function _initLocalName()
	{
		Yaf_Loader::getInstance()->registerLocalNamespace(array(
			'Smarty',  //Yaf_Loader::getInstance()->autoload("Smarty"); search '/library/'
		));
	}

	//初始化插件
	public function _initPlugin(Yaf_Dispatcher $dispatcher)
	{
//		$dispatcher->setErrorHandler(array($this, 'error'));
//
//		//注册插件 跳转地址
//		$handler = new DemoPlugin();
//		$dispatcher->registerPlugin($handler);
	}

	//初始化路由
	public function _initRoute(Yaf_Dispatcher $dispatcher)
	{

		//加载ini配置文件中的routes内容
		$router = $dispatcher->getInstance()->getRouter();
		$router->addConfig(Yaf_Registry::get("config")->routes);
		$routes = Yaf_Dispatcher::getInstance()->getRouter()->getRoutes();

	}

	//初始化smarty
	public function _initSmarty(Yaf_Dispatcher $dispatcher)
	{
		if(php_sapi_name() == "cli") return false; //命令行下不执行
		//Yaf_Loader::import("smarty/Adapter.php"); //没用，写在这里方便查

		$smarty = new Smarty_Adapter(null, Yaf_Registry::get("config")->get("smarty"));
		Yaf_Registry::set("smarty", $smarty);
		$dispatcher->setView($smarty); //启用smarty 如果注掉这一行将启用自带模板方案

		//关闭模板自动输出
		Yaf_Dispatcher::getInstance()->autoRender(FALSE);
	}

	//设置默认运行的文件名、方法名
	public function _initDefaultName(Yaf_Dispatcher $dispatcher)
	{
		if(php_sapi_name() == "cli") return false; //命令行下不执行
		/**
		 * actully this is unecessary, since all the parameters here is the default value of Yaf
		 */
		$dispatcher->setDefaultModule("User")->setDefaultController("Index")->setDefaultAction("index");
	}

	//初始自动加载的函数
	public function _initFunc(Yaf_Dispatcher $dispatcher)
	{
		if(php_sapi_name() == "cli") return false; //命令行下不执行
		Common_Func::Factory('dobreak');
	}

	//初始化post和get
	public function _initRequest(Yaf_Dispatcher $dispatcher)
	{
		if(php_sapi_name() == "cli") return false; //命令行下不执行
		Common_Func::Factory()->dorequest();
	}

}