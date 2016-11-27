<?php
function smarty_modifier_format_mobilephone($mobilephone) {
	if(!$mobilephone) {
		return ;
	}
	
	return substr($mobilephone, 0, 3).'****'.substr($mobilephone, -4);
}
