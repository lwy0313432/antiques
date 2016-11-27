<?php
class Common_ReturnCode{
	const ERR_CODE_NOT_EXIST = 101;
	const USER_NAME_NOT_VALID = 10001;
	const ADMIN_NOT_EXIST = 10002;
	const ADMIN_PASSWORD_ERR= 10003;
	const USER_ID_NOT_VALID= 10004;
	const OPERATE_LOG_TYPE_NOT_VALID= 10005;
	const INSERT_INTO_OPERATE_LOG_ERROR= 10006;
	const OLD_PASSWORD_ERROR= 10007;
	const PASSWORD_NOT_VALID=10008;
	const UPDATE_ADMIN_ERR = 10009;
	const PARAM_ERR = 10010;
	const ADMIN_PERMISSION_DENY = 10011;
	const USER_NOT_EXIST = 10012;
	const BIRTHDAY_NOT_VALID = 10013;
	const SEX_NOT_VALID = 10014;
	const REALNAME_NOT_VALID = 10015;
	const MOBILE_NOT_VALID = 10016;
	const ROLE_NOT_VALID = 10017;
	const INNER_ERR = 10018;
	const USER_ALREADY_EXIST = 10019;
	private static $code_arr=array(
		self::ERR_CODE_NOT_EXIST=>array('err_msg'=>'err_code_nos_exist','remark'=>'错误码不存在'),
		self::USER_NAME_NOT_VALID=>array('err_msg'=>'user_name_not_valid','remark'=>'用户名不合法'),
		self::ERR_CODE_NOT_EXIST=>array('err_msg'=>'err_code_not_exist','remark'=>'错误码不存在'),
		self::ADMIN_NOT_EXIST=>array('err_msg'=>'admin_not_exist','remark'=>'用户不存在'),
		self::ADMIN_PASSWORD_ERR =>array('err_msg'=>'admin_passwd_err','remark'=>'密码错误'),
		self::USER_ID_NOT_VALID  =>array('err_msg'=>'user_id_not_valid','remark'=>'用户id不合法'),
		self::USER_ID_NOT_VALID  =>array('err_msg'=>'operate_log_type_not_valid','remark'=>'OperateLog类型错误'),
		self::INSERT_INTO_OPERATE_LOG_ERROR=>array('err_msg'=>'insert_into_operate_log_err','remark'=>'插入OperateLog错误'),
		self::OLD_PASSWORD_ERROR=>array('err_msg'=>'old_password_err','remark'=>'旧密码错误'),
		self::PASSWORD_NOT_VALID=>array('err_msg'=>'password_not_valid','remark'=>'密码不合法'),
		self::UPDATE_ADMIN_ERR	=>array('err_msg'=>'update_admin_err','remark'=>'更新admin表失败'),
		self::PARAM_ERR  =>array('err_msg'=>'param_error','remark'=>'参数错误'),
		self::ADMIN_PERMISSION_DENY=>array('err_msg'=>'permission_deny','remark'=>'权限不足'),
		self::USER_NOT_EXIST=>array('err_msg'=>'user_not_exist','remark'=>'用户不存在'),
		self::BIRTHDAY_NOT_VALID=>array('err_msg'=>'birthday_not_valid','remark'=>'用户生日不合法'),
		self::SEX_NOT_VALID => array('err_msg'=>'sex_not_valid','remark'=>'用户性别不合法'),
		self::REALNAME_NOT_VALID => array('err_msg'=>'realname_not_valid','remark'=>'用户姓名不合法'),
		self::MOBILE_NOT_VALID => array('err_msg'=>'mobile_not_valid','remark'=>'用户手机号码不合法'),
		self::ROLE_NOT_VALID=> array('err_msg'=>'role_not_valid','remark'=>'用户角色不合法'),
		self::INNER_ERR=> array('err_msg'=>'inner_error','remark'=>'内部错误'),
		self::USER_ALREADY_EXIST=>array('err_msg'=>'user_already_exist','remark'=>'用户已存在'),
	);
	public static function err_ret($code){
		if(!array_key_exists($code,self::$code_arr)){
			return array(
				'err_code'=>self::ERR_CODE_NOT_EXIST,
				'err_msg'=>self::$code_arr[self::ERR_CODE_NOT_EXIST]['err_msg'],
				'remark'=>self::$code_arr[self::ERR_CODE_NOT_EXIST]['remark'],
			);
		}
		return array(
			'err_code'=>$code,
			'err_msg'=>self::$code_arr[$code]['err_msg'],
			'remark'=>self::$code_arr[$code]['remark'],
		);
	}
	public static function success_ret($ret_data=array()){
		return array(
			'err_code'=>0,
			'err_msg'=>'success',
			'remark'=>'成功',
			'data'=>$ret_data,
		);
	}
}