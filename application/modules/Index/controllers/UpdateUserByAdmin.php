<?php
/**
* 文件描述
*
* @author      liuwenyong 305381968@qq.com
* @date 2016-11-26 下午06:55:10
* @version 1.0.0
* @copyright  Copyright 2016 antiques.com
*/
class UpdateUserByAdminController extends BaseContro{
	public function IndexAction(){
		if(!$this->admin_id){//未登陆
			header("Location:/login");
			die;
		}
		
	}
}