<?php
/**
* 文件描述
*
* @author      liuwenyong 305381968@qq.com
* @date 2016-11-24 下午01:53:53
* @version 1.0.0
* @copyright  Copyright 2016 antiques.com
*/
class ModifyPasswordController extends BaseContro{

	public function IndexAction(){
		if(!$this->admin_id){//未登陆，则无法修改密码。
			header("Location:/login");
			die;
		}
		$old_passwd = isset($_REQUEST['old_passwd'])?$_REQUEST['old_passwd']:'';
		$new_passwd = isset($_REQUEST['new_passwd'])?$_REQUEST['new_passwd']:'';
 		$ret = Page_UserModel::modify_passwd($this->admin_id,$old_passwd,$new_passwd);
 		if($ret['err_code']==0){
 			Page_LogoutModel::admin_logout(); //退出登陆
 		}
		echo json_encode($ret);
 		 
	}
}