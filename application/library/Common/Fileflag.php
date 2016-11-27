<?php
/**
 * Yindou Framework
 * (C)2015-2020 Yindou Inc. (http://www.yindou.com)
 *
 * 脚本进程标记类
 * 用于保证每一个脚本同时只运行一次
 *
 * @Package Yindou Index
 * @version $Id: Fileflag.php 138 2015-06-16 07:24:21Z wuwenjia $
 **/

class Common_Fileflag{

	private static $key=null;
	private static $in=array();
	private static $file=array();
	private static $path=array();

	/**
	 * 工厂类
	 *
	 * @param  string $key     文件名
	 * @param  string $mode    文件打开方式 参见fopen系统函数
	 * @return array  $obj
	**/
	public static function Factory($key)
	{
		self::$key = $key;
		!isset(self::$in[self::$key]) && self::$in[self::$key]=new self();
		return self::$in[self::$key];
	}

	/**
	 * 初始化FC对象
	 *
	 * @param  string $mode    文件打开方式 参见fopen系统函数
	**/
	public function __construct()
	{
		self::$path[self::$key] = APP_PATH.DS.'application'.DS.'cache'.DS.'file_cache'.DS.self::$key.'.php';
		self::$file[self::$key] = fopen(self::$path[self::$key], 'w+');
	}

	/**
	 * 锁定文件
	 * 如不手动unlock则该进程结束自动解锁
	 *
	 * @return bool
	**/
	public function lock()
	{
		$file = self::$file[self::$key];
		return flock($file,LOCK_EX|LOCK_NB); //第二个参数防止阻塞用
	}

	/**
	 * 解锁文件
	 *
	 * @return bool
	**/
	public function unlock()
	{
		$file = self::$file[self::$key];
		return flock($file,LOCK_UN);
	}
}