<?php
class SendMsg{
	/*
	 * 上海创蓝公司的发短信接口
	 */
	public static function do_send($mobile,$content){
		$shcl_account = Yaf_Registry::get('config')->SMS_SHCL_ACCOUNT;
		$shcl_passwd = Yaf_Registry::get('config')->SMS_SHCL_PASS;
		$shcl_send_url = Yaf_Registry::get('config')->SMS_SHCL_SEND_URL;
		if(!$shcl_account || !$shcl_passwd || !$shcl_send_url){
			// 注：在本地测试时，将两个常量定义为空，返回true，但是实际上不发送短信
			return true;
		}
		if(!$content || !Common_Tools::is_mobile($mobile)){
			return false;
		}
		//创蓝接口参数
		$postArr = array (
				          'account' =>$shcl_account,
				          'pswd' => $shcl_passwd,
				          'msg' => $content,
				          'mobile' => $mobile,
				          'needstatus' => 'true',
				          'product' => '',
				          'extno' => ''
                     );
		$result = self::curlPost( $shcl_send_url , $postArr);
		/*返回两行，
		 * 20110725160412,0
			1234567890100
		 */
		$result=preg_split("/[,\r\n]/",$result);
		
		if (count($result)>=3 && $result[1]==0) { 
			return $result[2];
		} else {
			Data_LogModel::warning('上海创蓝短信发送失败',array('ret' => $result, 'mobile' => $mobile, 'content' => $content));
			return false;
		}
	}
	/**
	 * 查询余额
	 *
	 */
	public static function query_balance_shcl() {
		$shcl_account = Yaf_Registry::get('config')->SMS_SHCL_ACCOUNT;
		$shcl_passwd = Yaf_Registry::get('config')->SMS_SHCL_PASS;
		$shcl_query_balance_url = Yaf_Registry::get('config')->SMS_SHCL_QUERY_BALANCE_URL;
		//查询参数
		$postArr = array ( 
		          'account' => $shcl_account,
		          'pswd' => $shcl_passwd,
		);
		$result = self::curlPost($shcl_query_balance_url, $postArr);
		/*返回至少两行，第一行显示返回额度时的时间，提交响应值。
		 * 第二行开始，每一行显示一个产品ID及其额度，有多少个产品显示多少行。
		 * 20130303180000,0
			1234567,1000
			1234531,2000

		 */
		$result=preg_split("/[,\r\n]/",$result);
		var_export( $result );
		if (count($result)>=4 && $result[1]==0) { 
			return array('err_code'=>0,
						'err_msg'=>"success",
						"account_id_1"=>$result[2],
						'account_balance_1'=>$result[3]
					);
		} else {
			Data_LogModel::warning('上海创蓝短信发送失败',array('ret' => $result, 'mobile' => $mobile, 'content' => $content));
			return array('err_code'=>1,
						'err_msg'=>"query_failed",
					);
		}
		
		return $result;
	}
	/**
	 * 通过CURL发送HTTP请求
	 * @param string $url  //请求URL
	 * @param array $postFields //请求参数 
	 * @return mixed
	 */
	private static function curlPost($url,$postFields){
		$postFields = http_build_query($postFields);
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );
		$result = curl_exec ( $ch );
		curl_close ( $ch );
		return $result;
	}
	
	/**
	 * 美联软通,发短信
	 */
	public static function do_send_mlrt($mobile, $content)
	{
		$mlrt_account = Yaf_Registry::get('config')->SMS_MLRT_ACCOUNT;
		$mlrt_passwd = Yaf_Registry::get('config')->SMS_MLRT_PASS;
		$mlrt_api_key = Yaf_Registry::get('config')->SMS_MLRT_API_KEY;
		if (!$mlrt_account or !$mlrt_passwd or !$mlrt_api_key) {
			// 注：在本地测试时，将两个常量定义为空，返回true，但是实际上不发送短信
			return true;
		}
		$param['username'] = $mlrt_account; //'bjdfcy';
		$param['password'] = $mlrt_passwd; //'YinDou2910Zhaiquan01';
		$param['apikey'] = $mlrt_api_key;
		$param['mobile'] = $mobile;
		$param['content'] = iconv('utf-8', 'gbk', $content);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://m.5c.com.cn/api/send/');
		curl_setopt($ch, CURLOPT_PORT, 80);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                // 关闭输出，返回字符串
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_HEADER, false);                        // 返回header
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
		curl_setopt($ch, CURLOPT_NOSIGNAL, 1);    //注意，毫秒超时设置这个
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 5000);
		$ret = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);

		/**
		 * success:14212250156471
		 */
		if (strtolower(substr($ret, 0, 7)) == 'success') {
			$arr_temp = explode(":", $ret);
			if (count($arr_temp) >= 2) {
				return $arr_temp[1];
			} else {
				Data_LogModel::notice('手机验证码是否成功？', array('ret' => $ret));
				return true;
			}
		} else {
			Data_LogModel::warning('手机验证码 美联软通短信发送失败',array('ret' => $ret, 'mobile' => $mobile, 'content' => $content, 'curl_error' => $error));
			return false;
		}
	}

	/**
	 * 美联软通，绑定ip地址，指定，只有绑定的ip地址才能发短信。其他地址无法发送。
	 */
	public static function mlrt_bind_ip($ip)
	{
		$mlrt_account = Yaf_Registry::get('config')->SMS_MLRT_ACCOUNT;
		$mlrt_passwd = Yaf_Registry::get('config')->SMS_MLRT_PASS;
		$mlrt_api_key = Yaf_Registry::get('config')->SMS_MLRT_API_KEY;
		if (!$mlrt_account or !$mlrt_passwd or !$mlrt_api_key) {
			// 注：在本地测试时，将两个常量定义为空，返回true，但是实际上不发送短信
			return true;
		}

		$param['username'] = $mlrt_account; //'bjdfcy';
		$param['password'] = $mlrt_passwd; //'YinDou2910Zhaiquan01';
		$param['apikey'] = $mlrt_api_key;
		$param['ip'] = $ip;
		$param['action'] = 0;    //0 为绑定ip，1为查询，2为清空

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://m.5c.com.cn/api/bind/index.php');
		curl_setopt($ch, CURLOPT_PORT, 80);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                // 关闭输出，返回字符串
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_HEADER, false);                        // 返回header
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
		$ret = curl_exec($ch);
		curl_close($ch);
		/*
		  * 返回结果  success:Bind IP success
		  */
		if (strtolower(substr($ret, 0, 7)) == 'success') {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * 美联软通，查询发送状态报告，
	 */
	public static function mlrt_query_send_report()
	{
		$mlrt_account = Yaf_Registry::get('config')->SMS_MLRT_ACCOUNT;
		$mlrt_passwd = Yaf_Registry::get('config')->SMS_MLRT_PASS;
		$mlrt_api_key = Yaf_Registry::get('config')->SMS_MLRT_API_KEY;
		if (!$mlrt_account or !$mlrt_passwd or !$mlrt_api_key) {
			// 注：在本地测试时，将两个常量定义为空，返回true，但是实际上不发送短信
			return true;
		}

		$param['username'] = $mlrt_account; //'bjdfcy';
		$param['password'] = $mlrt_passwd; //'YinDou2910Zhaiquan01';
		$param['apikey'] = $mlrt_api_key;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://m.5c.com.cn/api/recv');
		curl_setopt($ch, CURLOPT_PORT, 80);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                // 关闭输出，返回字符串
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_HEADER, false);                        // 返回header
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
		$ret = curl_exec($ch);
		curl_close($ch);
		/*
		  * 返回结果  success:Bind IP success
		  */
		return $ret;

	}
}