<?php
/**
* 文件描述
*
* @author      liuwenyong 305381968@qq.com
* @date 2016-11-6 下午01:53:53
* @version 1.0.0
* @copyright  Copyright 2016 antiques.com
*/
class LoginController extends BaseContro{

	public function IndexAction(){ 
		if($this->admin_id){//已经登陆了，那么直接到首页
			header("Location:/");
			die;
		}
		 
 		echo self::getView()->render("login.tpl");
	}
}