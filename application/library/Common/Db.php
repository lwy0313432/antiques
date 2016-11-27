<?php
/**
 * Yindou Framework
 * (C)2015-2020 Yindou Inc. (http://www.yindou.com)
 *
 * DB基类
 *
 * @Package Yindou Index
 * @version $Id: Db.php 715 2015-08-10 04:04:48Z hanxiao $
 **/

class Common_Db
{
	private $err_info;
	private $db_name = "";
	private static $arr_instance = array();
	private static $db = array();

	public static function Factory($database_name)
	{
		!isset(self::$db[$database_name]) && self::$db[$database_name] = new self($database_name);
		return self::$db[$database_name];
	}

	public function __construct($db_name)
	{
		$this->db_name = $db_name;
		$this->get_instance($db_name);
	}

	private function get_instance($dbname)
	{
		if (empty(self::$arr_instance[$dbname])) {
			$db_conf = Yaf_Registry::get("config")->get("db");

			if (!isset($db_conf)) {//这里面不能在调用这个将日志写到数据库的方法，因为会循环调用。内存溢出
				error_log('db conf error');
				return false;
			}
			if (!isset($db_conf[$dbname])) {
				error_log('db_name is not set db_name='. $dbname);
				return false;
			}

			$db_host = $db_conf[$dbname]['dbhost'];
			$db_name = $db_conf[$dbname]['dbname'];
			$db_port = isset($db_conf[$dbname]['dbport']) ? $db_conf[$dbname]['dbport'] : 3306;
			$db_user = $db_conf[$dbname]['dbuser'];
			$db_dbpass = $db_conf[$dbname]['dbpass'];

			$obj_pdo = new PDO("mysql:host=" . $db_host . ";port=".$db_port.";dbname=" . $db_name, $db_user, $db_dbpass);
			
			self::$arr_instance[$dbname] = $obj_pdo;
			self::$arr_instance[$dbname]->query('SET NAMES UTF8');
		}
		return self::$arr_instance[$dbname];
	}

	/*
	 * 传入的$ql是带占位符的。
	 * $sql='select rights.id,rights.loan_id,loan.title from rights,loan loan.id=? and rights.id=?'
	 * $value=array("rights.loan_id",25);//数组元素的个数必须和占位符一致
	 */
	public function execute($sql, $value = array())
	{

		$obj_pdo = $this->get_instance($this->db_name);
		$sql = trim($sql);
		$sql = preg_replace('/\/\*.*?\*\//i', '', $sql);
		if (preg_match("/^INSERT/i", $sql)) {
			$stmt = $obj_pdo->prepare($sql);
			for ($i = 0; $i < count($value); $i++) {
				$stmt->bindparam($i + 1, $value[$i]);
			}
			$result = $stmt->execute();
			if ($result) {
				return $obj_pdo->lastInsertId();
			} else {
				$error_info = $stmt->errorInfo();
				Data_LogModel::warning("db error sql=$sql; err_msg=" . $error_info[2],array('value' => $value));
				return false;
			}
		} elseif (preg_match("/^(DELETE|UPDATE|REPLACE|DROP|CREATE|ALTER)/i", $sql)) {
			$stmt = $obj_pdo->prepare($sql);
			for ($i = 0; $i < count($value); $i++) {
				$stmt->bindparam($i + 1, $value[$i]);
			}
			$result = $stmt->execute();
			if ($result) {
				return $result;
			} else {
				$error_info = $stmt->errorInfo();
				Data_LogModel::warning("db error sql=$sql; err_msg=" . $error_info[2],array('value' => $value));
				
				return false;
			}
		} elseif (preg_match("/^(SELECT|SHOW|DESCRIBE|EXPLAIN)/i", $sql)) {
			$stmt = $obj_pdo->prepare($sql);
			for ($i = 0; $i < count($value); $i++) {
				$stmt->bindparam($i + 1, $value[$i]);
			}
			$result = $stmt->execute();
			if ($result) {
				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$error_info = $stmt->errorInfo();
				Data_LogModel::warning("db error sql=$sql; err_msg=" . $error_info[2],array('value' => $value));
				
				return false;
			}
		} elseif (preg_match("/^BEGIN$/i", $sql)) {
			return $obj_pdo->beginTransaction();
		} elseif (preg_match("/^COMMIT$/i", $sql)) {
			return $obj_pdo->commit();
		} elseif (preg_match("/^ROLLBACK$/i", $sql)) {
			return $obj_pdo->rollBack();
		} elseif (preg_match("/^end/i", $sql)) {

		} else {
			return false;
		}
	}

	public function start_trans()
	{
		return $this->get_instance($this->db_name)->beginTransaction();
	}

	public function commit()
	{
		return $this->get_instance($this->db_name)->commit();
	}

	public function rollback()
	{
		return $this->get_instance($this->db_name)->rollBack();
	}

	/*
	 * $fields="*" 或者 $fields="max(id),lender_id"
	 * $conds=array("id="=>1,"lender_id>"=>2),
	 * $groupby="lender_id".
	 * $orderby = "id asc,lender_id desc"
	 */
	public function select($tblname, $fields, $conds = null, $limit = 0, $offset = 0, $groupby = null, $orderby = null)
	{

		$limit = intval($limit);
		$offset = intval($offset);
		if ($limit < 0 || $offset < 0 || ($offset > 0 && $limit == 0)) {
			Data_LogModel::warning("limit or offset set invalid when select from $tblname. limit=$limit; offset=$offset");
				
			return false;
		}

		$sql = 'select ' . $fields . " from $tblname ";
		if (isset($conds)) {
			if (empty($conds) || !is_array($conds)) {
				Data_LogModel::warning("select from " . $tblname . " conds error;conds is empty or not an array");
				return false;
			}
			$check_conds = $this->split_where_conds($conds);
			if ($check_conds['err_code']) {
				Data_LogModel::warning( "select from " . $tblname . " conds error;conds is empty or not an array", array("conds" => $conds));
				return false;
			}
			$where_value = $check_conds['values'];
			$where_key = $check_conds['keys'];
			$sql .= " where " . implode(" and ", $where_key);
		}
		if (isset($groupby)) {
			$sql .= " group by $groupby ";
		}
		if (isset($orderby)) {
			$sql .= " order by $orderby ";
		}
		if ($limit > 0) {
			$sql .= " limit $offset , $limit";
		}
		$stmt = $this->get_instance($this->db_name)->prepare($sql);

		if (isset($conds)) {
			for ($i = 0; $i < count($where_value); $i++) {
				$stmt->bindparam($i + 1, $where_value[$i]);
			}
		}
		$result = $stmt->execute();
		if ($result) {
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$err_info = $stmt->errorInfo();
			Data_LogModel::warning(" sql execute error;prepare sql=[$sql].errmsg=" . $err_info[2], array("conds" => $conds, "groupby" => $groupby, "orderby" => $orderby));
			return false;
		}
	}

	/*
	 * $fields="*" 或者 $fields="max(id),lender_id"
	 * 
	 * $join_tables[] = array(
	 * 		'join_table'	=>	'join_table_name',	//[Required]
	 * 		'join_alias'	=>	'a',				//[Optional]
	 * 		'join_type'		=>	'left join',		//[Optional] Default join type: left join
	 * 		'join_columns'	=>	array(				//[Optional]
	 * 								'id='	=>	'main_table_name.borrower_id'
	 * 							)
	 * )
	 * 
	 * $conds=array("id="=>1,"lender_id>"=>2),
	 * $groupby="lender_id".
	 * $orderby = "id asc,lender_id desc"
	 */
	public function select_ext($main_table, $fields, $join_tables = null, $conds = null, $limit = 0, $offset = 0, $groupby = null, $orderby = null)
	{

		$limit = intval($limit);
		$offset = intval($offset);
		if ($limit < 0 || $offset < 0 || ($offset > 0 && $limit == 0)) {
			Data_LogModel::warning("limit or offset set invalid when select from $main_table. limit=$limit; offset=$offset");
				
			return false;
		}

		$sql = 'select ' . $fields . " from $main_table ";
		
		if(isset($join_tables) && is_array($join_tables) && !empty($join_tables)) {
			$join_types = array('left join', 'inner join', 'right join', 'join');
			foreach($join_tables as $join_table) {
				if(isset($join_table['join_table']) && !empty($join_table['join_table'])) {
					$join_table_name = trim($join_table['join_table']);
					$join_table_alias = '';
					if(isset($join_table['join_alias']) && !empty($join_table['join_alias'])) {
						$join_table_alias = $join_table['join_alias'];
					}
					
					$join_type = 'left join'; //Default join type: left join
					if(isset($join_table['join_type']) && in_array($join_table['join_type'], $join_types)) {
						$join_type = $join_table['join_type'];
					}
					$sql .= ' '.$join_type.' '.$join_table_name.' '.$join_table_alias.' ON ';
					$tmp_table_name = empty($join_table_alias) ? $join_table_name : $join_table_alias;
					
					if(isset($join_table['join_columns']) && is_array($join_table['join_columns']) && !empty($join_table['join_columns'])) {
						$tmp_join_keys = array();
						foreach($join_table['join_columns'] as $join_column_key => $join_column_target) {
							array_push($tmp_join_keys, $tmp_table_name.'.'.$join_column_key.$join_column_target);
						}
						$sql .= implode(' and ', $tmp_join_keys);
					} else {
						$sql .= $tmp_table_name.'.id='.$main_table.'.'.$join_table_name.'_id';
					}
				}
			}
		}
		if (isset($conds)) {
			if (empty($conds) || !is_array($conds)) {
				Data_LogModel::warning("select from " . $main_table . " conds error;conds is empty or not an array");
				return false;
			}
			$check_conds = $this->split_where_conds($conds);
			if ($check_conds['err_code']) {
				Data_LogModel::warning( "select from " . $main_table . " conds error;conds is empty or not an array", array("conds" => $conds));
				return false;
			}
			$where_value = $check_conds['values'];
			$where_key = $check_conds['keys'];
			$sql .= " where " . implode(" and ", $where_key);
		}
		if (isset($groupby)) {
			$sql .= " group by $groupby ";
		}
		if (isset($orderby)) {
			$sql .= " order by $orderby ";
		}
		if ($limit > 0) {
			$sql .= " limit $offset , $limit";
		}
		
		$stmt = $this->get_instance($this->db_name)->prepare($sql);
		if (isset($conds)) {
			for ($i = 0; $i < count($where_value); $i++) {
				$stmt->bindparam($i + 1, $where_value[$i]);
			}
		}
		$result = $stmt->execute();
		if ($result) {
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$err_info = $stmt->errorInfo();
			Data_LogModel::warning(" sql execute error;prepare sql=[$sql].errmsg=" . $err_info[2], array("conds" => $conds, "groupby" => $groupby, "orderby" => $orderby));
			return false;
		}
	}
	
	/*
	 * 
	 *   传入的参数是array("id"=>1,"lender_id"=>2)
	 */
	public function insert($tblname, $arr_input, $replace=false)
	{
		$str_do = $replace ? 'replace into ' : 'insert into ';

		$arr_fields = array_keys($arr_input);
		$arr_values = array_values($arr_input);

		if (empty($arr_fields) || empty($arr_values)) {
			Data_LogModel::warning($str_do . $tblname . " input error", array("arr_input" => $arr_input));
			return false;
		}

		$arr_tmp = array();
		for ($i = 0; $i < count($arr_fields); $i++) {
			$arr_tmp[] = "?";
		}
		$sql = $str_do . $tblname . " (" . implode(",", $arr_fields) . ") values";
		$sql .= "(" . implode(",", $arr_tmp) . ")";
		$stmt = $this->get_instance($this->db_name)->prepare($sql);
		for ($i = 0; $i < count($arr_values); $i++) {
			$stmt->bindparam($i + 1, $arr_values[$i]);
		}
		$result = $stmt->execute();
		if ($result) {
			return $this->get_instance($this->db_name)->lastInsertId();
		} else {
			$err_info = $stmt->errorInfo();
			Data_LogModel::warning("DB error;sql=[$sql];err_msg=" . $err_info[2], array("arr_input" => $arr_input));
			return false;
		}
	}

	/*
	 * $arr_input=('fields_name'=>"fields_value"...)
	 * $where_conds=array('id'=>1,'retry_times'=>3);
	 */
	public function update($tblname, $arr_input, $where_conds)
	{
		$arr_fields = array_keys($arr_input);
		$arr_values = array_values($arr_input);
		$check_conds = $this->split_where_conds($where_conds);

		if ($check_conds['err_code']) {
			Data_LogModel::warning("update  " . $tblname . " conds bad pattern", array("where_conds" => $where_conds));
			return false;
		}
		$where_value = $check_conds['values'];
		$where_key = $check_conds['keys'];


		if (empty($arr_fields) || empty($arr_values) || empty($where_key) || empty($where_value)) {
			Data_LogModel::warning("update  " . $tblname . " input error", array("arr_input" => $arr_input));
			return false;
		}

		$fields_tmp = array();
		foreach ($arr_fields as $fields) {
			$fields_tmp[] = "$fields=?";
		}

		$sql = "update " . $tblname . " set " . implode(",", $fields_tmp) . " where " . implode(" and ", $where_key);
		$stmt = $this->get_instance($this->db_name)->prepare($sql);

		foreach ($where_value as $key111 => $value111) { //将 两个数组合并。
			$arr_values[] = $value111;
		}
		for ($i = 0; $i < count($arr_values); $i++) {
			$stmt->bindparam($i + 1, $arr_values[$i]);
		}

		$result = $stmt->execute();
		$err_info = $stmt->errorInfo();
		if ($result && $err_info[1] == 0) {
			return $result;
		} else {
			Data_LogModel::warning( "DB error sql=[$sql];errmsg=" . $err_info[2], array("arr_input" => $arr_input, "where_conds" => $where_conds));
			return false;
		}
	}

	/*
	 * $conds = array('id='=>20);
	 * $conds = array('id in'=>'20,21');
	 */
	public function delete($tblname, $conds)
	{
		if (!is_array($conds) || empty($conds)) {
			Data_LogModel::warning( "delete from  " . $tblname . " conds must not be null", array("conds" => $conds));
			return false;
		}
		$check_conds = $this->split_where_conds($conds);
		if ($check_conds['err_code']) {
			Data_LogModel::warning(  "delete from  " . $tblname . " conds bad pattern", array("conds" => $conds));
			return false;
		}
		$where_value = $check_conds['values'];
		$where_key = $check_conds['keys'];
		$sql = "delete from $tblname" . " where " . implode(" and ", $where_key);
		$stmt = $this->get_instance($this->db_name)->prepare($sql);
		for ($i = 0; $i < count($where_value); $i++) {
			$stmt->bindparam($i + 1, $where_value[$i]);
		}
		$result = $stmt->execute();
		$err_info = $stmt->errorInfo();
		if ($result) {
			return true;
		} else {
			$err_info = $stmt->errorInfo();
			Data_LogModel::warning("DB error ; sql=[$sql];errmsg=" . $err_info[2], array("conds" => $conds));
			
			return false;
		}
	}

	/*
	 * 本函数用来分割where语句中的 条件表达式。
	 * 返回两个数字
	 */
	private function split_where_conds($arr_conds)
	{
		$ret = array("err_code" => 1, "err_msg" => " where conds error pattern");
		$ret_key = array();
		$ret_value = array();
		if (!is_array($arr_conds) || empty($arr_conds)) {

			return $ret;
		}
		foreach ($arr_conds as $key => $value) {
			if (is_array($value)) { //字段值不能再是数组
				return $ret;
			}
			if ($this->check_key_exist_in($key)) { // 如果是 "id in"=>"1,2,11"的情况
				$ret_key[] = "$key (" . $this->get_wenhao($value) . ")";
				$arr_value = explode(",", $value);
				if (is_array($arr_value)) {
					foreach ($arr_value as $key22 => $value22) {
						$arr_value[$key22] = trim($value22);
					}
				}
				if (empty($arr_value) || !$arr_value || !is_array($arr_value)) {
					Data_LogModel::warning(" conds bad pattern when check where conds", array("arr_conds" => $arr_conds));
					return $ret;
				}
				foreach ($arr_value as $value111) {
					$ret_value[] = $value111;
				}
			} else {
				$ret_key[] = "$key?";
				$ret_value[] = $value;
			}
		}
		return array("err_code" => 0, "err_msg" => "ok", "keys" => $ret_key, "values" => $ret_value);

	}

	/*
	 * conds=array("id in"=>"1,2,3,4")
	 * 本函数根据"1,2,3,4"用逗号隔开后的个数来返回 用? 隔开的个数
	 * 比如本例将返回"?,?,?,?"
	 */
	private function get_wenhao($value)
	{
		$arr = explode(",", $value);
		$ret = array();
		foreach ($arr as $item) {
			$ret[] = "?";
		}
		return implode(",", $ret);
	}

	/*
	 * 判断传进来的条件语句中，key值是否是fieldsname in 的形式
	 */
	private function check_key_exist_in($key)
	{
		if (!is_string($key)) {
			return false;
		}
		$arr = explode(" ", $key);
		if (in_array("in", $arr)) {
			return true;
		} else {
			return false;
		}
	}

	public function getErrorCode()
	{
		$err_info = $this->get_instance($this->db_name)->errorInfo();
		return intval($err_info[1]);

	}

	public function getErrorMsg()
	{
		$err_info = $this->get_instance($this->db_name)->errorInfo();
		if ($err_info[1] != 0) {
			return $err_info[2];
		}
	}

	public function getErrorInfo()
	{
		$err_info = $this->get_instance($this->db_name)->errorInfo();
		return $err_info;
	}

	public function getDB()
	{

		return $this->get_instance($this->db_name);
	}

}