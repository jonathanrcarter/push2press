<?php

$setup = array();
/*on
secti
key
help text
default val
type
sub-type
*/

$settings->types = array("general","display","security","other");
$settings->table = array(
	array("general","bgc1","Background color 1 - headings","#000000","text","colour"),
	array("general","bgc2","Background color 2 - page background","#ffffff","text","colour"),
	array("display","sitename","","","text",""),
	array("display","url","","","text","url"),
	array("display","appid","","","text","")
);

function is_in_settings($key) {
	global $settings;
	foreach ($settings->table as $settings_option) {
		if ($key == $settings_option[1]) return true;
	}
	return false;
}



?>