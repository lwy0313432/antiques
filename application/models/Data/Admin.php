<?php
class Data_AdminModel{
	const IS_DELETE_Y = 'y';
	const IS_DELETE_N = 'n';
	public static $is_delete=array(
		self::IS_DELETE_N,
		self::IS_DELETE_Y,
	);
	const SEX_MALE='male';
	const SEX_FEMALE='female';
	public static $sex=array(self::SEX_FEMALE,self::SEX_MALE);
	const ROLE_SUPER='super';
	const ROLE_EDIT ='eidt';
	const ROLE_VIEW = 'view';
	public static $role=array(
		self::ROLE_SUPER,
		self::ROLE_EDIT,
		self::ROLE_VIEW,
		);
	
	public static function add_user($param){
		$arr_in=array();
 
		if(!isset($param['user_name']) || !Common_Tools::is_member_username($param['user_name'])){
			Dao_LogModel::warning('添加用户用户名不合法',array('param'=>$param  ));
			return Common_ReturnCode::err_ret(Common_ReturnCode::USER_NAME_NOT_VALID);
		}
		$user_info = self::get_admin_by_user_name($param['user_name']);
		if($user_info['err_code']!=0  ){//有错误或者用户已存在
			return $user_info;
		}
		if( !empty($user_info['data'])){
			Dao_LogModel::warning('添加用户用户名已存在',array('param'=>$param  ));
			return Common_ReturnCode::err_ret(Common_ReturnCode::USER_ALREADY_EXIST);
		}
		$arr_in['user_name'] = $param['user_name'];
		if(!isset($param['password']) || !Common_Tools::is_valid_passwd($param['password'])){
			Dao_LogModel::warning('添加用户密码不合法',array('param'=>$param  ));
			return Common_ReturnCode::err_ret(Common_ReturnCode::PASSWORD_NOT_VALID);
		}
		$arr_in['password'] = md5($param['password']);
		if(!isset($param['birthday']) || !strtotime($param['birthday'])){
			Dao_LogModel::warning('添加用户生日不合法',array('param'=>$param  ));
			return Common_ReturnCode::err_ret(Common_ReturnCode::BIRTHDAY_NOT_VALID);
		}
		$arr_in['birthday'] = date("Y-m-d",strtotime($param['birthday']));
		if(!isset($param['sex']) || !in_array($param['sex'],Data_AdminModel::$sex)){
			Dao_LogModel::warning('添加用户性别不合法',array('param'=>$param  ));
			return Common_ReturnCode::err_ret(Common_ReturnCode::SEX_NOT_VALID);
		}
		$arr_in['sex'] =  $param['sex'] ;
		if(!isset($param['real_name']) || !Common_Tools::is_realname($param['real_name'])){
			Dao_LogModel::warning('添加用户姓名不合法',array('param'=>$param  ));
			return Common_ReturnCode::err_ret(Common_ReturnCode::REALNAME_NOT_VALID);
		}
		$arr_in['real_name'] =  $param['real_name'] ;
		if(!isset($param['mobile']) || !Common_Tools::is_mobile($param['mobile'])){
			Dao_LogModel::warning('添加用户手机号码不合法',array('param'=>$param  ));
			return Common_ReturnCode::err_ret(Common_ReturnCode::MOBILE_NOT_VALID);
		}
		$arr_in['mobile'] =  $param['mobile'] ;
		if(!isset($param['role']) || !in_array($param['role'],Data_AdminModel::$role)){
			Dao_LogModel::warning('添加用户角色不合法',array('param'=>$param  ));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ROLE_NOT_VALID);
		}
		$arr_in['role'] =  $param['role'] ;
		
 
		if(!isset($param['remark']) || !preg_match('/^[\x80-\xff]{0,}$/i', $param['remark'])){
			Dao_LogModel::warning('添加用户备注不合法',array('param'=>$param  ));
			return Common_ReturnCode::err_ret(Common_ReturnCode::ROLE_NOT_VALID);
		}
		$arr_in['remark'] =  $param['remark'] ;
		$arr_in['create_dt'] = date("Y-m-d H:i:s");
		$obj_dao = new Dao_AdminModel();
		$ret = $obj_dao->insert($arr_in);
		if(!$ret){
			Dao_LogModel::warning('添加用户数据库错误',array('param'=>$param,'ret'=>$ret  ));
			return Common_ReturnCode::err_ret(Common_ReturnCode::INNER_ERR);
		}
		return Common_ReturnCode::success_ret();
	}
	public static function get_admin_by_id($user_id){
		$user_id=intval($user_id);
		if($user_id<=0){
			return Common_ReturnCode::err_ret(Common_ReturnCode::USER_ID_NOT_VALID);
		}
		$obj_dao = new Dao_AdminModel();
		$arr_conds = array('id='=>$user_id);
		$admin = $obj_dao->select("*",$arr_conds);
		if(!$admin){
			$admin=array();
		}else{
			$admin=$admin[0];
		}
		return Common_ReturnCode::success_ret($admin);
	}
	public static function get_admin_by_user_name($user_name){
		if(!Common_Tools::is_member_username($user_name)){
			return Common_ReturnCode::err_ret(Common_ReturnCode::USER_NAME_NOT_VALID);
		}
		$obj_dao = new Dao_AdminModel();
		$arr_conds = array('user_name='=>$user_name);
		$admin = $obj_dao->select("*",$arr_conds);
		if(!$admin){
			$admin=array();
		}else{
			$admin=$admin[0];
		}
		return Common_ReturnCode::success_ret($admin);
	}
	/*
	 * 修改admin表的内容
	 */
	public static function update_admin($user_id,$update_fields){
		$conds = array('id='=>$user_id);
		$dao_admin = new Dao_AdminModel();
		if(isset($update_fields['password'])){
			$update_fields['password'] = md5($update_fields['password']);
		}
		$ret = $dao_admin->update($update_fields,$conds);
		if(!$ret){
			return Common_ReturnCode::err_ret(Common_ReturnCode::UPDATE_ADMIN_ERR);
		}
		return Common_ReturnCode::success_ret();
	}
	/*
	 * 获取管理员列表
	 * $fields='*'  或者具体的字段列表，字段之间以逗号隔开。
	 * $conds= array('id='=>1,'is_delete='=>'n');//数组或者空
	 * $limit=数字
	 * $offset=数字
	 * $group_by='id'
	 * $order_by='id asc,create_dt asc' 
	 */
	public static function get_user_list($fields,$conds=null,$limit,$offset,$group_by=null,$order_by=null){
		$dao_admin = new Dao_AdminModel();
		$limit = intval($limit);
		$offset=intval($offset);
		
		$ret = $dao_admin->select($fields,$conds,$limit,$offset,$group_by,$order_by);
		return Common_ReturnCode::success_ret($ret);
	}
	
	
}