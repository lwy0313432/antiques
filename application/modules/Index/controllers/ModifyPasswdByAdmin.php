<?php
/**
* 文件描述
*
* @author      liuwenyong 305381968@qq.com
* @date 2016-11-24 下午01:53:53
* @version 1.0.0
* @copyright  Copyright 2016 antiques.com
* 超级管理员，可以给任何用户修改密码。
*/
class ModifyPasswdByAdminController extends BaseContro{

	public function IndexAction(){
		if(!$this->admin_id){//未登陆，则无法修改密码。
			header("Location:/login");
			die;
		}
		$user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:'';
		$new_passwd = isset($_REQUEST['new_passwd'])?$_REQUEST['new_passwd']:'';
 		$ret = Page_UserModel::modify_passwd_by_admin($this->admin_id,$user_id,$new_passwd);
 		
		echo json_encode($ret);
 		 
	}
}