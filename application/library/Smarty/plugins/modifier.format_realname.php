<?php
function smarty_modifier_format_realname($realname) {

	$mb_len = mb_strlen($realname);
	if( $mb_len <= 0){
		return "";
	}elseif($mb_len ==1){
		return $realname;
	}elseif($mb_len==2){
		return mb_substr($realname, 0, 1,"utf-8").'*';
	}else{
		$ret_str= mb_substr($realname, 0, 1,"utf-8").' * ';
		$ret_str .= mb_substr($realname, $mb_len-1, 1,"utf-8");
		return $ret_str;
	}
	
} 
 