<?php
class Page_LoginModel{
	public static  function admin_login($user_name,$password){
		if(!Common_Tools::is_member_username($user_name)){
			return Common_ReturnCode::err_ret(Common_ReturnCode::USER_NAME_NOT_VALID);
		}
		$ret = Data_AdminModel::get_admin_by_user_name($user_name);

		if($ret['err_code']!=0){
			return $ret;
		}
		$admin = $ret['data'];
		if(!$admin){
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_NOT_EXIST);
		}
		if(md5($password)!=$admin['password']){
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_PASSWORD_ERR);
		}
		if($admin['is_delete']!='n'){
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_NOT_EXIST);
		}
		//创建session
		$session = Yaf_Session::getInstance();
		$session->set('id',$admin['id']);
		$session->set('user_name',$admin['user_name']);
		$session->set('real_name',$admin['real_name']);
		$session->set('mobile',$admin['mobile']);
		$session->set('role',$admin['role']);
		$session->set('is_delete',$admin['is_delete']);
		//写登陆日志  
		$arr_in = array('user_id'=>$admin['id'],'type'=>Data_OperateLogModel::TYPE_LOGIN);
		Data_OperateLogModel::add_log($arr_in);

		return Common_ReturnCode::success_ret();
	}
	
	
	
}