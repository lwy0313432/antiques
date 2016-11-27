<?php
class Data_OperateLogModel{
	const TYPE_LOGIN = 'login';
	const TYPE_ADD_USER = 'add_user';
	 
	public static $valid_type=array('login','add_user' );
	 public static function add_log($param){
	 	if(!isset($param['user_id']) || intval($param['user_id']) <=0){
	 		Dao_LogModel::warning('insert into operate_log user_id error',$param);
	 		return Common_ReturnCode::err_ret(Common_ReturnCode::USER_ID_NOT_VALID);
	 	}
		if(!isset($param['type']) || !in_array($param['type'],self::$valid_type) ){
			Dao_LogModel::warning('insert into operate_log type error',$param);
	 		return Common_ReturnCode::err_ret(Common_ReturnCode::USER_ID_NOT_VALID);
	 	}
	 	 
	 	$ip = Common_Tools::getip();
	 	$tmp = json_encode(array('ip'=>$ip));
	 	$arr_in = array(
	 		'user_id'=>intval($param['user_id']),
	 		'type'=>$param['type'],
	 		'dt'=>date("Y-m-d H:i:s"),
	 		'param'=>$tmp,
	 	);
	 	$dao_operatelog= new Dao_OperateLogModel();
	 	$ret = $dao_operatelog->insert($arr_in);
	 	if(!$ret){
	 		Dao_LogModel::warning('insert into operate_log error',$arr_in);
	 		return Common_ReturnCode::err_ret(Common_ReturnCode::INSERT_INTO_OPERATE_LOG_ERROR);
	 	}
	 	return Common_ReturnCode::success_ret();
	 	
	 }
}