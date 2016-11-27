<?php
/**
* 文件描述
*	删除用户
* @author      liuwenyong 305381968@qq.com
* @date 2016-11-6 下午01:53:53
* @version 1.0.0
* @copyright  Copyright 2016 antiques.com
*/
class DeleteUserController extends BaseContro{

	public function IndexAction(){ 
		if(!$this->admin_id){//未登陆，则跳到登陆页
			header("Location:/login");
			die;
		}
		$user_id = isset($_REQUEST['user_id'])? $_REQUEST['user_id'] :'';//删除的用户的id
		
		$ret = Page_UserModel::del_user($this->admin_id,$user_id);
		echo json_encode($ret);
	}
}