<?php
/**
 * Yindou Framework
 *
 * 获取gey
 *
 * @version $Id: func.doget.php 138 2015-06-16 07:24:21Z wuwenjia $
 **/

Common_Func::Factory('sqlchk');
if(get_magic_quotes_gpc()){
    function doget($name) {
        if(isset($_GET[$name])){
            return is_array($_GET[$name]) ? $_GET[$name] : sqlchk(trim($_GET[$name]));
        }else{
            return null;
        }
    }
}else{
    function doget($name) {
        if(isset($_GET[$name])){
            return is_array($_GET[$name]) ? $_GET[$name] : addslashes(sqlchk(trim($_GET[$name])));
        }else{
            return null;
        }
    }
}
