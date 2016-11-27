<?php
/**
* 文件描述
*	获取用户列表
* @author      liuwenyong 305381968@qq.com
* @date 2016-11-6 下午01:53:53
* @version 1.0.0
* @copyright  Copyright 2016 antiques.com
*/
class GetUserListController extends BaseContro{
	private $limit=20;
	public function IndexAction(){ 
		if(!$this->admin_id){//未登陆，则跳到登陆页
			header("Location:/login");
			die;
		}
		 
		if(isset($_REQUEST['limit']) &&  intval($_REQUEST['limit'])>0 ){
			$limit = intval($_REQUEST['limit']);
		}else{
			$limit = $this->limit;
		}
		if(isset($_REQUEST['page_num']) &&  intval($_REQUEST['page_num'])>0 ){
			$page_num = intval($_REQUEST['page_num']);
		}else{
			$page_num=1; //默认是第一页
		}
		$offset = ($page_num-1)*$limit;
		
		
		$ret = Page_UserModel::get_user_list($this->admin_id,$limit,$offset,'n');
		if($ret['err_code'] ==0){
			$data = $ret['data'];
			$ret_data =array(
				'user_list'=>$ret['data'],
				'limit'=>$limit,
				'page_num'=>$page_num
			); 
			$ret['data']=$ret_data;
		}
		Common_Tools::pre_echo($ret);die;
		echo json_encode($ret);
	}
}