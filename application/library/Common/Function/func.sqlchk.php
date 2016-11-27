<?php
/**
 * Yindou Framework
 *
 * 自动替换字符串中的sql敏感词
 *
 * @version $Id: func.sqlchk.php 138 2015-06-16 07:24:21Z wuwenjia $
 **/

function sqlchk($str)
{
    $chkStr=array('select ','insert ',' and ',' or ','update ','delete ',' * ',' #','../','./',' un
ion ',' into ','load_file ','outfile ','eval(','script');
    $rplStr=array('_select ','_insert ','_and ',' _or ','_update ','_delete ',' _* ','_#','_../','_
./',' _union ',' _into ','_load_file ','_outfile ','_eval(','_script');
    return str_ireplace($chkStr,$rplStr,$str);
}
