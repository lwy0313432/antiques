<?php
class Dao_AdminModel extends Dao_CommonAntiquesModel{
	private static $table='admin';
	public function __construct(){
		parent::__construct(self::$table);
	}
 
}