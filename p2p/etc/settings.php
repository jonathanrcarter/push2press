<?php

/** This file is part of push2press
  *
  *      @desc Settings
  *   @package push2press
  *    @author Jonathan Carter <jc@glimworm.com>
  * @copyright 2013 glimworm IT BV
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://www.push2press.com
  */

  
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