<?php
 
class Page_UserModel{
	/*
	 * 用户自己修改自己的密码。
	 */
	public static  function modify_passwd($user_id,$old_pass,$new_pass){
		$user_id = intval($user_id); 
		if(!Common_Tools::is_valid_passwd($new_pass)){
			Dao_LogModel::warning('修改密码新密码不合法',array('user_id'=>$user_id,'new_pass'=>$new_pass));
			return Common_ReturnCode::err_ret(Common_ReturnCode::PASSWORD_NOT_VALID);
		}
		$ret= Data_AdminModel::get_admin_by_id($user_id);
		if($ret['err_code']!=0){
			Dao_LogModel::warning('修改密码获取用户失败',array('user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_NOT_EXIST);
		}
		$admin = $ret['data'];
		
		if(!$admin){
			Dao_LogModel::warning('修改密码获取用户失败',array('user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_NOT_EXIST);
		}
		if($admin['password'] != md5($old_pass)){
			Dao_LogModel::warning('修改密码旧密码错误',array('user_id'=>$user_id,'old_pass'=>$old_pass));
			return Common_ReturnCode::err_ret(Common_ReturnCode::OLD_PASSWORD_ERROR);
		}
		$arr_in = array(
			'password'=>$new_pass,
		);
		$update_ret  =  Data_AdminModel::update_admin($user_id,$arr_in);
		if($update_ret['err_code'] ==0){
			return Common_ReturnCode::success_ret();
		}else{
			return $update_ret;
		}
		
	}
	/*
	 * 管理员给某用户修改密码。
	 * $admin_id是管理员的id，必须是超级管理员才有权限
	 * $user_id 是需要修改密码的用户id
	 * $new_pass 是需要修改密码的用户的新密码。
	 */
	public static function modify_passwd_by_admin($admin_id,$user_id,$new_pass){
		$user_id = intval($user_id); 
		$admin_id=intval($admin_id);
		if(!Common_Tools::is_valid_passwd($new_pass)){
			Dao_LogModel::warning('修改密码新密码不合法',array('admin_id'=>$admin_id,'user_id'=>$user_id,'new_pass'=>$new_pass));
			return Common_ReturnCode::err_ret(Common_ReturnCode::PASSWORD_NOT_VALID);
		}
		if($admin_id<=0 || $user_id<=0    ){
			Dao_LogModel::warning('管理员给用户修改密码时参数错误',array('admin_id'=>$admin_id,'user_id'=>$user_id,'new_pass'=>$new_pass));
			return Common_ReturnCode::err_ret(Common_ReturnCode::PARAM_ERR);
		}
		$ret= Data_AdminModel::get_admin_by_id($admin_id);
		if($ret['err_code']!=0 || !$ret['data']){
			Dao_LogModel::warning('管理员给用户修改密码时管理员不存在',array('admin_id'=>$admin_id,'user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_NOT_EXIST);
		}
		$admin = $ret['data'];
		if(!isset($admin['role']) || $admin['role']!='super'){
			Dao_LogModel::warning('管理员给用户修改密码,权限不够',array('admin_id'=>$admin_id,'user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_PERMISSION_DENY);
		}
		$ret= Data_AdminModel::get_admin_by_id($user_id); //获取被修改密码的用户信息
		if($ret['err_code']!=0 || !$ret['data']){
			Dao_LogModel::warning('管理员给用户修改密码时用户不存在',array('admin_id'=>$admin_id,'user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_NOT_EXIST);
		}
		 
		
		$arr_in = array(
			'password'=>$new_pass,
		);
		$update_ret  =  Data_AdminModel::update_admin($user_id,$arr_in);
		if($update_ret['err_code'] ==0){
			return Common_ReturnCode::success_ret();
		}else{
			return $update_ret;
		}
	}
	/*
	 * 删除用户
	 * $admin_id是删除者的id
	 * $user_id是被删除者的id
	 */
	public static function del_user($admin_id,$user_id){
		$user_id = intval($user_id); 
		$admin_id=intval($admin_id);
		
		if($admin_id<=0 || $user_id<=0    ){
			Dao_LogModel::warning('管理员删除用户时参数错误',array('admin_id'=>$admin_id,'user_id'=>$user_id));
			return Common_ReturnCode::err_ret(Common_ReturnCode::PARAM_ERR);
		}
		$ret= Data_AdminModel::get_admin_by_id($admin_id);
		if($ret['err_code']!=0 || !$ret['data']){
			Dao_LogModel::warning('管理员删除用户时管理员不存在',array('admin_id'=>$admin_id,'user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_NOT_EXIST);
		}
		$admin = $ret['data'];
		if(!isset($admin['role']) || $admin['role']!='super'){
			Dao_LogModel::warning('管理员删除用户权限不够',array('admin_id'=>$admin_id,'user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_PERMISSION_DENY);
		}
		$ret= Data_AdminModel::get_admin_by_id($user_id); //获取被删除的用户信息
		if($ret['err_code']!=0 || !$ret['data']){
			Dao_LogModel::warning('管理员删除用户时被用户不存在',array('admin_id'=>$admin_id,'user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::USER_NOT_EXIST);
		}
		$arr_in = array(
			'is_delete'=>'y',
		);
		$update_ret  =  Data_AdminModel::update_admin($user_id,$arr_in);
		if($update_ret['err_code'] ==0){
			return Common_ReturnCode::success_ret();
		}else{
			return $update_ret;
		}
		
		
	}
	/*
	 * 获取用户列表
	 * 超级管理员才有权限获取用户列表。
	 * 
	 */
	public static function get_user_list($admin_id,$limit=20,$offset=0,$is_delete='all'){
		$admin_id=intval($admin_id);
		$limit = intval($limit);
		$offset= intval($offset);
		if($admin_id<=0 || $limit<=0 || $offset<0 || !is_string($is_delete)){
			Dao_LogModel::warning('获取用户列表时参数错误',array('admin_id'=>$admin_id,'limit'=>$limit,'offset'=>$offset,'is_delete'=>$is_delete));
			return Common_ReturnCode::err_ret(Common_ReturnCode::PARAM_ERR);
		}
		$ret= Data_AdminModel::get_admin_by_id($admin_id);
		if($ret['err_code']!=0 || !$ret['data']){
			Dao_LogModel::warning(' 管理员不存在',array('admin_id'=>$admin_id,'user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_NOT_EXIST);
		}
		$admin = $ret['data'];
		if(!isset($admin['role'])  || $admin['role']!='super' ){
			Dao_LogModel::warning('管理员权限不够',array('admin_id'=>$admin_id ,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_PERMISSION_DENY);
		}
		if(in_array($is_delete,Data_AdminModel::$is_delete)){
			$arr_conds=array("is_delete="=>$is_delete);
		}else{
			$arr_conds=null;
		}
		$list = Data_AdminModel::get_user_list("*",$arr_conds,$limit,$offset);
		if($list['err_code']==0){
			return Common_ReturnCode::success_ret($list['data']);
		}else{
			return $ret;
		}
		
	}
	/*
	 * 添加用户接口
	 */
	public static function add_user($admin_id,$param){
 		$admin_id=intval($admin_id);
 		if($admin_id<=0){
 			Dao_LogModel::warning('参数错误',array('admin_id'=>$admin_id,'param'=>$param));
			return Common_ReturnCode::err_ret(Common_ReturnCode::PARAM_ERR);
 		}
 		$ret = Data_AdminModel::get_admin_by_id($admin_id);
 		if( $ret['err_code']!=0 || !$ret['data']){
 			Dao_LogModel::warning('管理员不存在',array('admin_id'=>$admin_id,'param'=>$param));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_NOT_EXIST);
 		}
 		$admin = $ret['data'];
 		if(!isset($admin['role']) || $admin['role']!=Data_AdminModel::ROLE_SUPER){
 			Dao_LogModel::warning('管理员权限不够',array('admin_id'=>$admin_id ,'admin'=>$admin));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_PERMISSION_DENY);
 		}
		return Data_AdminModel::add_user($param);
	}
	/*
	 * 管理员获取某个用户的信息
	 */
	public static function get_user_by_admin($admin_id,$user_id){
		$user_id = intval($user_id); 
		$admin_id=intval($admin_id);
		
		if($admin_id<=0 || $user_id<=0    ){
			Dao_LogModel::warning('管理员获取用户信息时参数错误',array('admin_id'=>$admin_id,'user_id'=>$user_id));
			return Common_ReturnCode::err_ret(Common_ReturnCode::PARAM_ERR);
		}
		$ret= Data_AdminModel::get_admin_by_id($admin_id);
		if($ret['err_code']!=0 || !$ret['data']){
			Dao_LogModel::warning('管理员获取用户信息时管理员不存在',array('admin_id'=>$admin_id,'user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_NOT_EXIST);
		}
		$admin = $ret['data'];
		if(!isset($admin['role']) || $admin['role']!='super'){
			Dao_LogModel::warning('管理员获取用户信息时权限不够',array('admin_id'=>$admin_id,'user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ADMIN_PERMISSION_DENY);
		}
		$ret= Data_AdminModel::get_admin_by_id($user_id); //获取用户信息
		if($ret['err_code']!=0 || !$ret['data']){
			Dao_LogModel::warning('管理员获取用户信息时被用户不存在',array('admin_id'=>$admin_id,'user_id'=>$user_id,'ret'=>$ret));
			return Common_ReturnCode::err_ret(Common_ReturnCode::USER_NOT_EXIST);
		}
		return Common_ReturnCode::success_ret($ret['data']);
	}
	
	
}