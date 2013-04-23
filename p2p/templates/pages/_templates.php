<?php

/** This file is part of push2press
  *
  *      @desc list of templates Language file
  *   @package push2press
  *    @author Jonathan Carter <jc@glimworm.com>, Paul Manwaring <paul@glimworm.com>
  * @copyright 2013 glimworm IT BV
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://www.push2press.com
  */

$_page_templates = array(
	"template" => "standard template",
	"template_nohead" => "template with no heading"
	);


$dir = "templates/pages/";
$files = scandir($dir);
foreach ($files as $file) {
	if (strpos($file,".html")) {
		$fls = explode(".", $file,2);
		if ($_page_templates[$fls[0]] == false) {
			$_page_templates[$fls[0]] = $fls[0];
		}
	}
}

?>