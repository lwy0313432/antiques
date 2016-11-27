<?php
/**
* 文件描述
*
* @author      liuwenyong 305381968@qq.com
* @date 2016-11-26 下午03:18:51
* @version 1.0.0
* @copyright  Copyright 2016 antiques.com
*/
class AddUserController extends BaseContro{
	public function IndexAction(){ 
		if(!$this->admin_id){//未登陆，那么直接到登陆页
			header("Location:/login");
			die;
		}
		
		$param['user_name'] = isset($_REQUEST['user_name'])? $_REQUEST['user_name'] :'';
		$param['password'] = isset($_REQUEST['password']) ? $_REQUEST['password']:'';
		$param['real_name'] = isset($_REQUEST['real_name'])? $_REQUEST['real_name'] :'';
		$param['sex'] = isset($_REQUEST['sex']) ? $_REQUEST['sex']:'';
		$param['mobile'] = isset($_REQUEST['mobile'])? $_REQUEST['mobile'] :'';
		$param['birthday'] = isset($_REQUEST['birthday']) ? $_REQUEST['birthday']:'';
		$param['role'] = isset($_REQUEST['role'])? $_REQUEST['role'] :'';
		$param['remark'] = isset($_REQUEST['remark']) ? $_REQUEST['remark']:'';
		$ret = Page_UserModel::add_user($this->admin_id,$param);
		echo json_encode($ret);
	}
}