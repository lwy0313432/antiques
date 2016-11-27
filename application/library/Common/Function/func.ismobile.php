<?php
/**
 * Yindou Framework
 *
 * 判断是否是手机
 *
 * @version $Id: func.ismobile.php 138 2015-06-16 07:24:21Z wuwenjia $
 **/

function ismobile ($str) {
	return preg_match('/^1[34578]\d{9}$/', $str);
}

function is_email ($str) {
    $pattern="/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";
    if(preg_match($pattern,$str,$counts)){
        return true;
    }else{
        return false;
    }
}