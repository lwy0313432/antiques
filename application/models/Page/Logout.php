<?php
 
class Page_LogoutModel{
public static  function admin_logout(){
		 
		//销毁session
		$session = Yaf_Session::getInstance();
		$session->set('id','');
		$session->set('user_name','');
		$session->set('real_name','');
		$session->set('mobile','');
		$session->set('role','');
		$session->set('is_delete','');
		//写登陆日志 需要补充
		return Common_ReturnCode::success_ret();
	}
}