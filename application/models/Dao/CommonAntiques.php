<?php

/**
 * Yindou Framework
 * (C)2015-2020 Yindou Inc. (http://www.yindou.com)
 *
 * DB公共类，每个库一个类，该类对应的库名字是antiques
 **/
class Dao_CommonAntiquesModel
{
	private static $table_name = null;
	private static $database_name = 'antiques';
	public function __construct($table_name) {
		if(!$table_name){
			return false;
		}
		self::$table_name = $table_name;
	}

	//方法同 Common_Db::Factory('r')->select
	public function select($fields, $conds = null, $limit = 0, $offset = 0, $groupby = null, $orderby = null)
	{
		return Common_Db::Factory(self::$database_name)->select(self::$table_name, $fields, $conds, $limit, $offset, $groupby, $orderby);
	}
	
	 

	//方法同 Common_Db::Factory('w')->update
	public function update($arr_input, $where_conds)
	{
		return Common_Db::Factory(self::$database_name)->update(self::$table_name, $arr_input, $where_conds);
	}

	//方法同 Common_Db::Factory('w')->insert
	public function insert($arr_input, $replace=false)
	{
		return Common_Db::Factory(self::$database_name)->insert(self::$table_name, $arr_input, $replace);
	}

	//方法同 Common_Db::Factory('w')->delete
	public function delete($conds)
	{
		return Common_Db::Factory(self::$database_name)->delete(self::$table_name, $conds);
	}

 

	//事务开始
	public function start_trans(){
		Common_Db::Factory(self::$database_name)->start_trans();
	}

	//事务结束
	public function commit(){
		Common_Db::Factory(self::$database_name)->commit();
	}

	//执行回滚
	public function rollback(){
		Common_Db::Factory(self::$database_name)->rollback();
	}

	//执行sql语句 慎用
	public function execute($sql, $value = array()){
		return Common_Db::Factory(self::$database_name)->execute($sql, $value);
	}

}
