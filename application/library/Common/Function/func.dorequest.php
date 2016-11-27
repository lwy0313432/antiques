<?php
/**
 * Yindou Framework
 *
 * 获取gey
 *
 * @version $Id: func.dorequest.php 138 2015-06-16 07:24:21Z wuwenjia $
 **/

Common_Func::Factory('sqlchk','doget','dopost');

function dorequest() {

	if(count($_GET) > 0){
		foreach($_GET as $k=>$v){
			$_GET[$k] = doget($k);
		}
	}

	if(count($_POST) > 0){
		foreach($_POST as $k=>$v){
			$_POST[$k] = dopost($k);
		}
	}
}
