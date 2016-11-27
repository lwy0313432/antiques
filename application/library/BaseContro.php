<?php
class BaseContro extends Yaf_Controller_Abstract{
	public $admin_id;
	public $user_name; 
	final function init(){
		$session = Yaf_Session::getInstance();
		$this->admin_id=  $session->get("id");
		$this->user_name = $session->get("user_name");
		self::getView()->assign("user_name", $this->user_name); 
	}
}