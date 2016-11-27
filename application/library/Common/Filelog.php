<?php
/**
 * Yindou Framework
 *
 * filelog基类
 * 文件缓存err_code为13000系列
 *
 * @version $Id: Filelog.php 171 2015-06-23 06:51:13Z wuwenjia $
 **/
!defined('YD_FCACHE_LIFE') && define('YD_FCACHE_LIFE', 31536000); //默认一年
class Common_Filelog
{

	//Fcache配置文件
	private $fc = array(
		'froot' => '',                //缓存跟目录
		'type' => 0,                  //采用什么方式储存
		'fmod' => 0755,               //写入文件的权限
		'fext' => '.php',             //写入文件的后缀
		'fdef' => array(),            //默认结果集合
	);
	private static $action = null;
	private static $in = array();
	public static function Factory($action)
	{
		if(!preg_match('/^\w+$/i',$action)){
			Data_LogModel::warning("filelog action error ; action=[{$action}]", array('err_code'=>'13301','err_msg'=>'filelog action error','data'=>array('action'=>$action)));
			return array('err_code'=>'13301','err_msg'=>'filelog action error');
		}

		if (!isset(self::$in[$action])){
			self::$in[$action] = new self();

			$path_root = APP_PATH.DS.'application'.DS.'cache'.DS.$action.'_log';
			if(!is_dir($path_root)){
				$flag = mkdir($path_root, self::$in[$action] ->fc['fmod'], true);
				if(!$flag){
					Data_LogModel::warning("filelog can not mkdir ; dirname=[{$path_root}]", array('err_code'=>'13304','err_msg'=>'filelog can not mkdir','data'=>array('dirname'=>$path_root)));
					return array('err_code'=>13304, 'err_msg'=>'filelog can not mkdir');
				}
			}

			self::$in[$action]->fc['froot'] = $path_root.DS.date('Ym');
			if(!is_dir(self::$in[$action]->fc['froot'])){
				$flag = mkdir(self::$in[$action] ->fc['froot'], self::$in[$action] ->fc['fmod'], true);
				if(!$flag){
					Data_LogModel::warning("filelog can not mkdir ; dirname=[".self::$in[$action]->fc['froot']."]", array('err_code'=>'13305','err_msg'=>'filelog can not mkdir','data'=>array('dirname'=>self::$in[$action]->fc['froot'])));
					return array('err_code'=>13305, 'err_msg'=>'filelog can not mkdir');
				}
			}
		}

		self::$action = $action;
		return self::$in[$action];
	}

	public function test(){
		die('i am test now!');
	}

	//新增log日志
	public function add($key, $value, $mode='add')
	{
		if(!preg_match('/^[a-z_]\w*$/i',$key)){
			Data_LogModel::warning("filelog add key error ; key=[{$key}]", array('err_code'=>'13302','err_msg'=>'filelog add key error','data'=>array('key'=>$key, 'value'=>$value)));
			return array('err_code'=>'13302','err_msg'=>'filelog add key error');
		}

		$filename = self::$in[self::$action] ->fc['froot'].DS.$key.self::$in[self::$action] ->fc['fext'];
		$time = date('Y-m-d H:i:s');
		$content = "[{$time}] {$value} \r\n";
		$mode = $mode=='lock' ? 'lock' : 'add';
		if($mode == 'lock'){
			$fp = fopen($filename, 'a');
			flock($fp, LOCK_EX) ;
			fwrite($fp,$content);
			flock($fp, LOCK_UN);
			$flag = fclose($fp);
		}else{
			$flag = file_put_contents($filename, $content, FILE_APPEND);
		}

		if($flag){
			return array('err_code'=>0, 'err_msg'=>'success');
		}else{
			Data_LogModel::warning("filelog can not write ; filename=[{$filename}]", array('err_code'=>'13303','err_msg'=>'filelog can not write','data'=>array('filename'=>$filename)));
			return array('err_code'=>13303, 'err_msg'=>'filelog can not write');
		}
	}

}