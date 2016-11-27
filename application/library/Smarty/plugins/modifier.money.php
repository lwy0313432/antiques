<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty spacify modifier plugin
 * 
 * Type:     modifier<br>
 * Name:     spacify<br>
 * Purpose:  add spaces between characters in a string
 * 
 * @link http://smarty.php.net/manual/en/language.modifier.spacify.php spacify (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com> 
 * @param string $string       input string
 * @param string $spacify_char string to insert between characters.
 * @return string
 */
function smarty_modifier_money($string, $unit="分")
{
	if(!$string){
		return '0';
	}
	$moneyVal = intval($string);
	if($unit == '元'){
		$moneyVal *= 100; 
	}
	$ret = '';
	$cent = $moneyVal % 100;
	$moneyVal = intval($moneyVal/100);
	if($moneyVal >= 10000){
		$ret .= intval($moneyVal/10000) . '万';
	}
	$moneyVal = $moneyVal % 10000;
	if($moneyVal){
		$ret .= $moneyVal;
	}
	$ret .='元';

	if($cent){
		$ret .=$cent .'分';
	}
	

	return $ret;
} 

?>