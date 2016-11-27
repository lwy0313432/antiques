<?php
/**
 * Yindou Framework
 *
 * 自动加载函数库里的函数
 *
 * @version $Id: Func.php 433 2015-07-20 10:19:39Z wuwenjia $
 **/

class Common_Func
{
    private $fdir=null;
    private $fitem=array();
    private static $in=null;

	/**
	 * 对象静态初始化并载入存储在[Function]目录的函数库
	 *
	 * @Example:
	 *          静态加载: Fend_Func::factory('dopost')
	 *          静态加载: Fend_Func::factory('dopost','doget')
	 *          动态加载: Fend_Func::factory()->dopost();
	 * @param  string 函数名,多个
	 * @return Object
	 **/
    public static function Factory()
    {
        if(null===self::$in){
            self::$in = new self();
            self::$in->fdir=APP_PATH.DS.'application'.DS.'library'.DS.'Common'.DS.'Function'.DS;
            $GLOBALS['_YD_FUNC']=&self::$in->fitem;
        }
        $args=func_get_args();

        foreach($args as $v) self::$in->isFunction($v);
        return self::$in;
    }

	/**
	 * 检测并载入函数,私密方法供内部使用
	 *
	 * @param  string 函数名
	 * @return null
	 **/
    private function isFunction($fn)
    {
        $fn=strtolower($fn);
        if(!in_array($fn,$this->fitem)){
            if(is_file($this->fdir.'func.'.$fn.'.php')){
                include_once($this->fdir.'func.'.$fn.'.php');
            }else{
				Data_LogModel::red('common_function not exist', array('function' => $fn));
            }
            $this->fitem[]=$fn;
        }
    }

	/**
	 * 魔法函数: 自动载入对象中不存在的方法
	 *
	 * @param  string  函数名
	 * @return resource
	 **/
    public function __call($fn,$fv)
    {
        self::isFunction($fn);
        return call_user_func_array($fn,$fv);
    }
    
    
    public static function set_curl($url,$content,$method=1){
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, $method );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT , 10 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT , 25 );  
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $content );
		$return = curl_exec ( $ch );
		curl_close ( $ch );
		return $return;
	}  
}
?>