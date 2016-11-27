<?php
/**
* 文件描述
*
* @author      liuwenyong 305381968@qq.com
* @date 2016-11-6 下午01:53:53
* @version 1.0.0
* @copyright  Copyright 2016 antiques.com
*/
class ActionLoginController extends BaseContro{

	public function IndexAction(){ 
		if($this->admin_id){//已经登陆了，那么直接到首页
			header("Location:/");
			die;
		}
		$user_name = isset($_REQUEST['user_name'])? $_REQUEST['user_name'] :'';
		$password = isset($_REQUEST['password']) ? $_REQUEST['password']:'';
		$ret = Page_LoginModel::admin_login($user_name,$password);
		echo json_encode($ret);
	}
}