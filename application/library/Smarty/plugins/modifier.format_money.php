<?php
function smarty_modifier_format_money($cent) {
	$cent = intval($cent);
	if ($cent === 0) {
		return '0.00元';
	}
	if ($cent > 0) {		// 正数
		$is_positive = true;
	} else {				// 负数
		$is_positive = false;
	}
	$cent = abs($cent);
	
	if ($cent >= 1000000) {		// 万元以上，显示为万
        $return = sprintf('%.2f', $cent/10000/100).'万';
	} else {					// 万元以下，显示为元
		$return = sprintf('%.2f', $cent/100).'元';
	}
	
	if ($is_positive) {
		return $return;
	} else {
		return '-'.$return;		// 负数的前面加上负号
	}
} 
?>
