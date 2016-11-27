<?php
function smarty_modifier_format_username($username) {
	if (!$username) {
		return '';
	}
	return '*'.mb_strimwidth($username, 1, 3).'**';
} 
?>
