<?php
/**
 * Yindou Framework
 * (C)2015-2020 Yindou Inc. (http://www.yindou.com)
 *
 * 缓存基类
 *
 * @Package Yindou Index
 * @version $Id: Cache.php 746 2015-08-21 09:04:18Z wuwenjia $
 **/

class Common_Cache
{
	private static $in = null;

	public static function Factory($t=0)
	{
		switch($t){
			case 0:
				self::$in = Common_Cache_Filecache::Factory();
				break;
			case 1:
				$handle = Common_Cache_Memcache::Factory('user');
				if($handle){
					self::$in = $handle;
				}else{
					self::$in = Common_Cache_Filecache::Factory();
				}
				break;
			case 2:
				$handle = Common_Cache_Memcache::Factory('data');

				if($handle){
					self::$in = $handle;
				}else{
					self::$in = Common_Cache_Filecache::Factory();
				}
				break;
			case 3:
				$handle = Common_Cache_Redis::Factory();

				if($handle){
					self::$in = $handle;
				}else{
					self::$in = Common_Cache_Filecache::Factory();
				}
				break;
			default:
				Data_LogModel::warning('cache type error', array('t'=>$t));
				break;
		}

		return self::$in;
	}

}

