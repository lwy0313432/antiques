<?php
/**
 * Yindou Framework
 *
 * 生成随机数
 *
 * @version $Id: func.rancode.php 138 2015-06-16 07:24:21Z wuwenjia $
 **/

function rancode($length, $numeric = 0)
{
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($numeric){
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	}else{
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()_+{}:"<>?';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}
