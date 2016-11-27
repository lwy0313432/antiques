<?php
/**
 * Yindou Framework
 *
 * 获取post
 *
 * @version $Id: func.dopost.php 138 2015-06-16 07:24:21Z wuwenjia $
 **/

Common_Func::Factory('sqlchk');
if(get_magic_quotes_gpc()){
    function dopost($name) {
        if(isset($_POST[$name])){
            return is_array($_POST[$name]) ? $_POST[$name] : sqlchk(trim($_POST[$name]));
        }else{
            return null;
        }
    }
}else{
    function dopost($name) {
        if(isset($_POST[$name])){
            return is_array($_POST[$name]) ? $_POST[$name] : addslashes(sqlchk(trim($_POST[$name])));
        }else{
            return null;
        }
    }
}
