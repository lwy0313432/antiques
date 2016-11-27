<?php
/**
* 文件描述
*
* @author      liuwenyong 305381968@qq.com
* @date 2016-11-26 下午06:58:28
* @version 1.0.0
* @copyright  Copyright 2016 antiques.com
*/
class GetUserInfoController extends BaseContro{
	public function IndexAction(){
		if(!$this->admin_id){
			header("Location:/login");
			die;
		}
		$user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:'';
		$ret = Page_UserModel::get_user_by_admin($this->admin_id,$user_id);
		Common_Tools::pre_echo($param);
		echo json_encode($ret);
	}
}