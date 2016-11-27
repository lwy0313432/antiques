<?php
/*
 * 传进来单位是元比如10000，
 * 将金额转换为1,0000.00
 */

function smarty_modifier_thousands($string)
{
	
	$yuan =  sprintf("%.2f",$string); 
	 
	$arr = explode(".",$yuan);
	$value1 = $arr[0];
	$value2 = $arr[1];
	$length = strlen($value1);
	$j=1;
	$retstr="";
	for($i=$length-1; $i>=0; $i--){
		$char = substr($value1,$i,1); 
		if( $j%3 == 0 && $j < $length){
			$char = ",".$char;
		}
		$j++;
		$retstr  =$char . $retstr;
	}
	$retstr = $retstr.".".$value2;

    return $retstr;
} 