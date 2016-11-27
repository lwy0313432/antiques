<?php
class Dao_OperateLogModel extends Dao_CommonAntiquesModel{
	private static $table='operate_log';
	public function __construct(){
		parent::__construct(self::$table);
	}
 
}