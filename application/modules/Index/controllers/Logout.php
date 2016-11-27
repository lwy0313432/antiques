<?php
/**
* 文件描述
*
* @author      liuwenyong 305381968@qq.com
* @date 2016-11-24 下午01:53:53
* @version 1.0.0
* @copyright  Copyright 2016 antiques.com
*/
class LogoutController extends BaseContro{

	public function IndexAction(){ 
		if(!$this->admin_id){//已经登陆了，那么直接到首页
			header("Location:/login");
			die;
		}
		
		$ret = Page_LogoutModel::admin_logout( );
		if(isset($ret['err_code']) && $ret['err_code']==0){
			header("Location:/login");
			die;
		} 
		echo json_encode($ret);
	}
}