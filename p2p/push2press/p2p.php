<?php

class Config {


	const dbhost = "localhost";
	const username = "s88005cp_root";
	const password = "glimworm";
	const database = "s88005cp_p2p";
	const images_folder = "/client_images/";
	const BASEPATH = "/";
	const MASTER_PASSWORD="utrecht";

}




class obj {
}


class pushnote {

	function __construct() {
	}

}


class push2press {

	const INSTALLED = 0;
	const NEEDS_SETUP = 1;

	function log2($MSG,$STATUS) {
	
		$db = mysql_connect(Config::dbhost, Config::username, Config::password);
       	mysql_select_db($database) or die("Unable to select database");
       	mysql_query("SET NAMES utf8", $db);
       	mysql_query( "SET CHARACTER SET utf8", $db );
       	$query="insert ignore into log values(0,now(),'".$MSG."','".$STATUS."')";
       	$result=mysql_query($query);
	}
	

	function __construct() {
	}
	
	function isInstalled_db() {
		$db = mysql_connect(Config::dbhost, Config::username, Config::password);
       	mysql_select_db($database) or return NEEDS_SETUP;
       	mysql_query("SET NAMES utf8", $db);
       	mysql_query( "SET CHARACTER SET utf8", $db );
		
	}
	
	function isInstalled() {
		$status = $this->isInstalled_db();
		if ($status == $this->NEEDS_SETUP) {
			require_once 'install.php';
			return;
		}
		
	}

}


$p2p = new push2press();
$p2p->isInstalled();
echo "Is Installed";

?>
