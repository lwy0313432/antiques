<?php
function smarty_modifier_format_idcard($card) {
	if (!$card) {
		return '';
	}
	$length=strlen($card);
	return substr($card, 0, 4).'********'.substr($card, -4);
}
?>
