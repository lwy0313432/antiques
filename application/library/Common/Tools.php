<?php
/**
 * Yindou Framework
 * (C)2015-2020 Yindou Inc. (http://www.yindou.com)
 *
 * 公用工具库
 *
 * @Package Yindou Index
 * @version $Id: Tools.php 1058 2015-09-28 06:43:52Z wuwenjia $
 **/

class Common_Tools{
	public static function pre_echo ($param) {
		if (is_array($param)) {
			echo '<pre>'.print_r($param, true).'</pre>'."\r\n";
		} else {
			echo '<pre>'.$param.'</pre>'."\r\n";
		}
	}

	/*
	 * 函数功能，实现乘100的功能，防止浮点乘100，然后转为整型 时不准确的问题
	 * $in="2.01"
	 */
	public static function multiply100($in){
	    $in = "$in"; //强制转换为字符串
	    if(false === strpos($in,".")){
	        //是整数
	        return  intval($in)*100;
	    
	    }else{
	        $arr = explode(".",$in);
	        if($arr[0] == ""){ //处理 ".123"这种浮点数，
	            $arr[0]=0;
	        }   
	        $in = $arr[0].".".$arr[1]."00"; //先加2个0，防止位数不足
	    
	        $pos = strpos($in,".");
	        $ret = substr($in,0,$pos).substr($in,$pos+1,2);
	        return intval($ret);
	    }   
	}
	
	public static function is_member_username ($username) {
		if (preg_match('/^[0-9A-Za-z_.@-]{5,25}$/', $username)) {
				return strtolower($username);
		} else return false;
	}

	public static function is_email ($str) {
		$pattern="/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";
		if(preg_match($pattern,$str,$counts)){
			return true;
		}else{
			return false;
		}
	}

	public static function is_mobile ($str) {
		return preg_match('/^1[34578]\d{9}$/', $str);
	}

	//校验真名
	public static function is_realname ($str) {
		return preg_match('/^[\x80-\xff]{2,}$/i', $str);
	}

	//校验身份证
	public static function is_id_card_num ($str) {

		//判断是不是18位和末位是否合法
		if(!preg_match('/^\d{17}[0-9x]$/i', $str)) return false;

		//分解身份证信息
		$array = array();
		$array['year'] = (int)substr($str,6,4);
		$array['month'] = (int)substr($str,10,2);
		$array['day'] = (int)substr($str,12,2);

		if($array['year']>date('Y')-18) return false; //未满18岁
		if($array['year']<date('Y')-120) return false; //超过120岁
		if($array['month']>12) return false; //月份错误
		if($array['day']>31) return false; //日期错误

		//位数加权码
		$jiaquan = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
		$ycode = array(1,0,'x',9,8,7,6,5,4,3,2);

		//最后一位校验
		$sum = 0;
		for($i=0;$i<17;$i++){
			$sum += $str[$i] * $jiaquan[$i];
		}

		$Y = $sum%11;
		$lastNum = $ycode[$Y];

		if(strtolower($str[17])!=$lastNum) return false;

		return true;
	}

	public static function is_bank_card_num ($str) {
		return preg_match('/^\d{16,19}$|^\d{6}[- ]\d{10,13}$|^\d{4}[- ]\d{4}[- ]\d{4}[- ]\d{4,7}$/', $str);
	}

	public static function is_id_code ($id_code) {
		return is_md5($id_code);
	}

	public static function is_md5 ($str) {
		return preg_match('/^[0-9a-f]{32}$/i', $str);
	}

	public static function is_flag ($flag) {
		return preg_match('/^[-0-9A-Za-z_]+$/', $flag);
	}

	public static function is_valid_passwd($passwd){
		return preg_match('/^[0-9a-zA-Z\~\!\@\#\$\%\^\&\*\(\)\_\-\+\=\{\}\[\]\;\'\:\"\<\,\>\.\?\/]{8,20}$/', $passwd);
	}

	public static function is_valid_mobile_code($code){
		return preg_match('/^[0-9]{4}$/', $code);
	}

	/*
	 * 获取ip地址
	 */
	public static function getip(){

		if(getenv('HTTP_X_REAL_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_REAL_FORWARDED_FOR'),'unknown')){
			$ip=getenv('HTTP_X_REAL_FORWARDED_FOR');
		}
		elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){
			$ip=getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){
			$ip=getenv('HTTP_CLIENT_IP');
		}elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){
			$ip=getenv('REMOTE_ADDR');
		}elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		$ip=preg_replace("/^([\d\.]+).*/","\\1",$ip);
		return $ip;
	}
	public static function is_sn($sn){
	 
		return preg_match('/^[0-9a-zA-Z-]+$/i', $sn) ? true : false;
	 
	}
	//分转换成元
	public static function fen2yuan($fen){
		return sprintf('%.2f', ($fen/100));
	}

	//到明天还剩多少秒
	public static function leftSecond(){
		$tomorrow = array();
		$tomorrow['second'] = time() + 3600 * 24;
		$tomorrow['Y'] = date('Y', $tomorrow['second']);
		$tomorrow['m'] = date('m', $tomorrow['second']);
		$tomorrow['d'] = date('d', $tomorrow['second']);

		return mktime(0, 0, 0, $tomorrow['m'], $tomorrow['d'], $tomorrow['Y']) - time();
	}

	//手机号马赛克
	public static function mobile_star($mobile){
		return substr($mobile,0,3).'****'.substr($mobile,7);
	}

	//处理网址 如果不是以/结尾 加上/
	public static function url_tail($url){
		return substr($url,-1)=='/' ? $url : $url.'/';
	}

	//获取用户openid
	public static function get_openid(){
		return Yaf_Session::getInstance()->get('openid_now');
	}

	//判断是否设置跳转网址
	public static function is_ref_url(){
		$controller_array = array('bonus_invite','bonus_received','huodong_xinbinlaizhan');
		preg_match('/^\/([a-z_]+)/i', $_SERVER['REQUEST_URI'], $controller);
		return isset($controller[1]) && in_array($controller[1], $controller_array);
	}

	//设置跳转网址
	public static function set_ref_url($url=null){
		$url = $url ? $url : Common_Tools::get_http_head().$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$rs = Yaf_Session::getInstance()->set('ref_url',$url);
		if(!$rs){
			Data_LogModel::notice('微信登录跳转地址session设置失败',array('url'=>$url));
		}
		return $rs;
	}

	//获取跳转网址
	public static function get_ref_url(){
		$url = Yaf_Session::getInstance()->get('ref_url');
		if($url) Yaf_Session::getInstance()->del('ref_url');

		return $url;
	}

	//获取http头
	public static function get_http_head(){
		return (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';
	}

}