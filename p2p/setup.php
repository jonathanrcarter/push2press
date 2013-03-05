<?php

/** This file is part of push2press
  *
  *      @desc Main admin code
  *   @package push2press
  *    @author Jonathan Carter <jc@glimworm.com>
  * @copyright 2013 glimworm IT BV
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://www.push2press.com
  */
 
$htop = "";
$htop = $htop .'<!DOCTYPE html>';
$htop = $htop .'<html lang="en">';
$htop = $htop .'  <head>';
$htop = $htop .'    <meta charset="utf-8">';
$htop = $htop .'    <title>Push 2 Press</title>';
$htop = $htop .'    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
$htop = $htop .'    <meta name="description" content="">';
$htop = $htop .'    <meta name="author" content="">';
$htop = $htop .'    <!-- Le styles -->';
$htop = $htop .'    <link href="http://www.glimworm.com/_assets/moock/bootstrap/css/bootstrap.css" rel="stylesheet">';
$htop = $htop .'	<link rel="stylesheet" type="text/css" href="lib/css/prettify.css">';
$htop = $htop .'    <link href="http://www.glimworm.com/_assets/moock/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">';
$htop = $htop .'    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->';
$htop = $htop .'    <!--[if lt IE 9]>';
$htop = $htop .'      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>';
$htop = $htop .'    <![endif]-->';
$htop = $htop .'    <!-- Le fav and touch icons -->';
$htop = $htop .'    <link rel="shortcut icon" href="http://www.glimworm.com/_assets/moock/bootstrap/ico/favicon.ico">';
$htop = $htop .'    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://www.glimworm.com/_assets/moock/bootstrap/ico/apple-touch-icon-144-precomposed.png">';
$htop = $htop .'    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://www.glimworm.com/_assets/moock/bootstrap/ico/apple-touch-icon-114-precomposed.png">';
$htop = $htop .'    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://www.glimworm.com/_assets/moock/bootstrap/ico/apple-touch-icon-72-precomposed.png">';
$htop = $htop .'    <link rel="apple-touch-icon-precomposed" href="http://www.glimworm.com/_assets/moock/bootstrap/ico/apple-touch-icon-57-precomposed.png">';
$htop = $htop .'    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />';
$htop = $htop .'    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>';
$htop = $htop .'    <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>';
$htop = $htop .'    <link href="http://www.glimworm.com/_assets/moock/bootstrap/extras/colorpicker/css/colorpicker.css" rel="stylesheet">';
$htop = $htop .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/extras/colorpicker/js/bootstrap-colorpicker.js"></script>';


$htop = $htop .'<link href="api.css" rel="stylesheet">';
$htop = $htop .'<script src="api.js"></script>';
$htop = $htop .'  </head>';
$htop = $htop .'  <body>';
$htop = $htop .'    <div class="navbar navbar-fixed-top">';
$htop = $htop .'      <div class="navbar-inner">';
$htop = $htop .'        <div class="container">';
$htop = $htop .'          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">';
$htop = $htop .'            <span class="icon-bar"></span>';
$htop = $htop .'            <span class="icon-bar"></span>';
$htop = $htop .'            <span class="icon-bar"></span>';
$htop = $htop .'          </a>';
$htop = $htop .'          <img src="images/application-logo.png">';
$htop = $htop .'          <div class="nav-collapse">';
$htop = $htop .'            <ul class="nav">';
$htop = $htop .'              <li><div style="padding-left:20px;padding-top: 9px;"><span class="label xlabel-inverse">push2press v1.1 ALPHA</span></div></li>';
$htop = $htop .'            </ul>';
$htop = $htop .'          </div>';
$htop = $htop .'        </div>';
$htop = $htop .'      </div>';
$htop = $htop .'    </div>';
$htop = $htop .'    <div class="container">';

$hbot = "";
$hbot = $hbot .'      <footer>';
$hbot = $hbot .'        <p>&copy; Glimworm 2012</p>';
$hbot = $hbot .'      </footer>';
$hbot = $hbot .'      </div>';
$hbot = $hbot . '<div class="modal hide fade" id="modal-window"></div>';
$hbot = $hbot . '<div class="modal hide fade" id="modal-window2"></div>';
$hbot = $hbot .'    <!-- Le javascript';
$hbot = $hbot .'    ================================================== -->';
$hbot = $hbot .'    <!-- Placed at the end of the document so the pages load faster -->';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-transition.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-alert.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-modal.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-dropdown.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-scrollspy.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-tab.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-tooltip.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-popover.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-button.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-collapse.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-carousel.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-typeahead.js"></script>';
$hbot = $hbot .'  </body>';
$hbot = $hbot .'</html>';


/*
$dbhost = "";
$username = "";
$password = "";
$database = "";
$images_folder = "";
$BASEPATH = "";
$MASTER_PASSWORD="";
*/
$D = "$";

require_once('./local_config.php');
$step = $_POST["step"];

function table_exists($tablename) {
	global $username,$password,$database,$dbhost;
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database);
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
    $res = mysql_query("
        SELECT COUNT(*) AS count 
        FROM information_schema.tables 
        WHERE table_schema = '$database' 
        AND table_name = '$tablename'
    ");
    return mysql_result($res, 0) == 1;
}
function getConfiguration($VAL,$DEFAULT_VAL) {
	global $username,$password,$database,$dbhost;
	try {
		$db = mysql_connect($dbhost,$username,$password);
		mysql_select_db($database);
		mysql_query("SET NAMES utf8", $db);
		mysql_query( "SET CHARACTER SET utf8", $db );
		$query="select * from domain where Pagename = '".$VAL."'";
		$result=mysql_query($query);
	
		if (mysql_numrows($result) > 0) {
			return mysql_result($result,0,"Caption");
		} else {
			return $DEFAULT_VAL;
		}
	} catch (Exception $e) {
		return $DEFAULT_VAL;
	}
}


if ($step == "2") {

	$dbhost = ($_POST["dbhost"] != "") ? $_POST["dbhost"] : $dbhost;
	$username = ($_POST["username"] != "") ? $_POST["username"] : $username;
	$password = ($_POST["password"] != "") ? $_POST["password"] : $password;
	$database = ($_POST["database"] != "") ? $_POST["database"] : $database;
	$images_folder = ($_POST["images_folder"] != "") ? $_POST["images_folder"] : $images_folder;
	$BASEPATH = ($_POST["BASEPATH"] != "") ? $_POST["BASEPATH"] : $BASEPATH;
	$MASTER_PASSWORD = ($_POST["MASTER_PASSWORD"] != "" ) ? $_POST["MASTER_PASSWORD"] : $MASTER_PASSWORD;
	$lang = ($_POST["lang"] != "") ? $_POST["lang"] : $lang;

	$h = "";
	$h = $h . "<?php\n";
	$h = $h . $D . "dbhost='$dbhost';\n";
	$h = $h . $D . "username='$username';\n";
	$h = $h . $D . "password='$password';\n";
	$h = $h . $D . "database='$database';\n";
	$h = $h . $D . "lang='$lang';\n";
	$h = $h . $D . "images_folder='$images_folder';\n";
	$h = $h . $D . "BASEPATH='$BASEPATH';\n";
	$h = $h . $D . "MASTER_PASSWORD='$MASTER_PASSWORD';\n";
	$h = $h . "?>\n";
	file_put_contents("./local_config.php", $h);
	$setupstep = 1;
	$err = false;


	if (!$err) {
		$db = mysql_connect($dbhost,$username,$password);
		if (!$db) {
			$setupstep = 1;
		    $setuperror =  'Error creating database: ' . mysql_error() . "\n";
			$err = true;
		}
	}

	if (!$err) {
		if (!mysql_select_db($database)) {
		
			$sql = 'CREATE DATABASE ' . $database;
			if (!mysql_query($sql, $db)) {
				$setupstep = 1;
			    $setuperror =  'Error creating database: ' . mysql_error() . "\n";
				$err = true;
			} else {
				if (!mysql_select_db($database)) {
					$setupstep = 1;
				    $setuperror =  'Error creating database: ' . mysql_error() . "\n";
					$err = true;
				}
			}	
		}
	}

	if (!$err) {
		mysql_query("SET NAMES utf8", $db);
		mysql_query( "SET CHARACTER SET utf8", $db );
	}

	
	/*
//	cats
//	domain
//	groups
//	log
//	log_phone
	menu
//	message
	pages
	pushmessages
	*/
	if (!$err) {
		$setuperror = "";
		if (!table_exists("log_phone")) {
			$sql = "CREATE TABLE `log_phone` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `uid` varchar(250) NOT NULL,
  `gid` int(2) NOT NULL,
  `dtype` varchar(10) NOT NULL DEFAULT '',
  `osn` varchar(25) NOT NULL DEFAULT '',
  `aid` varchar(255) NOT NULL DEFAULT '',
  `un` varchar(100) NOT NULL DEFAULT '',
  `fb` varchar(100) NOT NULL DEFAULT '',
  `en` varchar(100) NOT NULL DEFAULT '',
  `twit` varchar(100) NOT NULL DEFAULT '',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL DEFAULT '',
  `groups` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `log_phone_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
       		mysql_query($sql);
			$setuperror = $setuperror . "created table log_phone<br>";

		} else {
			$setuperror = $setuperror . "table exists log_phone<br>";
		}
		
		// next
		if (!table_exists("cats")) {
			$sql = "CREATE TABLE `cats` (
  `ID` int(6) NOT NULL AUTO_INCREMENT,
  `Pagename` varchar(255) NOT NULL DEFAULT '',
  `Caption` varchar(255) NOT NULL DEFAULT '',
  `img` varchar(255) NOT NULL DEFAULT '',
  `Volgorde` int(6) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
       		mysql_query($sql);
			$setuperror = $setuperror . "created table cats<br>";

		} else {
			$setuperror = $setuperror . "table exists cats<br>";
		}

		// next
		if (!table_exists("domain")) {
			$sql = "CREATE TABLE `domain` (
  `ID` int(6) NOT NULL AUTO_INCREMENT,
  `Pagename` varchar(255) NOT NULL DEFAULT '',
  `Caption` varchar(255) NOT NULL DEFAULT '',
  `img` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
       		mysql_query($sql);
			$setuperror = $setuperror . "created table domain<br>";

		} else {
			$setuperror = $setuperror . "table exists domain<br>";
		}

		// next
		if (!table_exists("groups")) {
			$sql = "CREATE TABLE `groups` (
  `gid` int(2) NOT NULL AUTO_INCREMENT,
  `gname` varchar(250) NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
       		mysql_query($sql);
			$setuperror = $setuperror . "created table groups<br>";

		} else {
			$setuperror = $setuperror . "table exists groups<br>";
		}

		// next
		if (!table_exists("log")) {
			$sql = "CREATE TABLE `log` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `data` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
       		mysql_query($sql);
			$setuperror = $setuperror . "created table log<br>";

		} else {
			$setuperror = $setuperror . "table exists log<br>";
		}


		// next
		if (!table_exists("message")) {
			$sql = " CREATE TABLE `message` (
  `ID` int(6) NOT NULL AUTO_INCREMENT,
  `Pagename` varchar(255) NOT NULL DEFAULT '',
  `Caption` varchar(255) NOT NULL DEFAULT '',
  `img` varchar(255) NOT NULL DEFAULT '',
  `bodytext` text NOT NULL,
  `Volgorde` varchar(255) NOT NULL DEFAULT '',
  `CatID` varchar(255) NOT NULL DEFAULT '',
  `ts_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ts_last_edited` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(20) NOT NULL DEFAULT '',
  `ts_sent` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
       		mysql_query($sql);
			$setuperror = $setuperror . "created table message<br>";

		} else {
			$setuperror = $setuperror . "table exists message<br>";
		}

		// next
		if (!table_exists("pages")) {
			$sql = "CREATE TABLE `pages` (
  `ID` int(6) NOT NULL AUTO_INCREMENT,
  `Pagename` varchar(255) NOT NULL DEFAULT '',
  `Caption` varchar(255) NOT NULL DEFAULT '',
  `img` varchar(255) NOT NULL DEFAULT '',
  `bodytext` text NOT NULL,
  `Volgorde` int(6) NOT NULL,
  `CatID` varchar(255) NOT NULL DEFAULT '',
  `template` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `extraData` varchar(250) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
       		mysql_query($sql);
			$setuperror = $setuperror . "created table pages<br>";

		} else {
			$setuperror = $setuperror . "table exists pages<br>";
		}

		// next
		if (!table_exists("pushmessages")) {
			$sql = "CREATE TABLE `pushmessages` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `data` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
       		mysql_query($sql);
			$setuperror = $setuperror . "created table pushmessages<br>";

		} else {
			$setuperror = $setuperror . "table exists pushmessages<br>";
		}

		// next
		if (!table_exists("recipient")) {
			$sql = "CREATE TABLE `recipient` (
  `rid` int(6) NOT NULL,
  `mid` int(6) NOT NULL AUTO_INCREMENT,
  `devID` varchar(512) NOT NULL,
  `osn` varchar(512) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
       		mysql_query($sql);
			$setuperror = $setuperror . "created table recipient<br>";

		} else {
			$setuperror = $setuperror . "table exists recipient<br>";
		}



		// next
		if (!table_exists("sending")) {
			$sql = "CREATE TABLE `sending` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eid` int(6) NOT NULL,
  `notification` varchar(512) NOT NULL,
  `msgDesc` varchar(512) NOT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
       		mysql_query($sql);
			$setuperror = $setuperror . "created table sending<br>";

		} else {
			$setuperror = $setuperror . "table exists sending<br>";
		}


	$dbhost = ($_POST["dbhost"] != "") ? $_POST["dbhost"] : $dbhost;
		
		
		
		if (getConfiguration("bgc1",null) == null) {
			mysql_query("insert into domain (Pagename,Caption) values('bgc1','".$_POST["bgc1"]."')");
			$setuperror = $setuperror . "added bgc1 to setup<br>";
		}
		if (getConfiguration("bgc2",null) == null) {
			mysql_query("insert into domain (Pagename,Caption) values('bgc2','".$_POST["bgc2"]."')");
			$setuperror = $setuperror . "added bgc2 to setup<br>";
		}
		if (getConfiguration("sitename",null) == null) {
			mysql_query("insert into domain (Pagename,Caption) values('sitename','".$_POST["sitename"]."')");
			$setuperror = $setuperror . "added sitename to setup<br>";
		}
		if (getConfiguration("url",null) == null) {
			mysql_query("insert into domain (Pagename,Caption) values('url','".$_POST["url"]."')");
			$setuperror = $setuperror . "added url to setup<br>";
		}
		if (getConfiguration("appid",null) == null) {
			mysql_query("insert into domain (Pagename,Caption) values('appid','".$_POST["appid"]."')");
			$setuperror = $setuperror . "added appid<br>";
		}
		if (getConfiguration("adminemail",null) == null) {
			mysql_query("insert into domain (Pagename,Caption) values('adminemail','".$_POST["adminemail"]."')");
			$setuperror = $setuperror . "added adminemail to adminemail<br>";
		}
		if (getConfiguration("setup",null) == null) {
			mysql_query("insert ignore into cats (ID,Pagename,Caption,img,Volgorde) values (1,'Home','Home','',0)");
			mysql_query("insert ignore into pages (ID,Pagename,Caption,img,bodytext,CatID,template,Volgorde,type,extraData) values (1,'Home','Home','','<p>Welcome to your push2press app</p>',1,'',0,'','{ \"navBar\" : \"y\" }')");
			mysql_query("insert ignore into groups (gid,gname) values (1,'General messages')");
			mysql_query("insert into domain (Pagename,Caption) values('setup','installed')");
		}
		
	}
	
	if (!$err) {
		$setupstep = 2;
	}
	
}

if ($setupstep == 2) {

	echo $htop;
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<h1>Setup - Step 2 of 2</h1>";
	echo "<br>";
	echo "<div>$setuperror</div>";
	echo "<br>";
	echo "<div><a href='api.php'>You can proceed to your site</a></div>";
	echo "<br>";
	echo "<br>";
	echo $hbot;
	
	exit;


}


if ($dbhost == "") $dbhost = "localhost";
if ($database == "") $database = "push2press";
if ($images_folder == "") $images_folder = "/client_images/";
if ($BASEPATH == "") $BASEPATH = "/";
if ($MASTER_PASSWORD == "") $MASTER_PASSWORD = "push2press";
if ($lang == "") $lang = "en";

$sitename = getConfiguration("sitename", ($_POST["sitename"] != "") ? ($_POST["sitename"]) : "My push2press app");
$url = getConfiguration("url","http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
$url = str_replace("setup.php", "", $url);


$bgc1 = getConfiguration("bgc1",($_POST["bgc1"] != "") ? ($_POST["bgc1"]) :"#000000");
$bgc2 = getConfiguration("bgc2",($_POST["bgc2"] != "") ? ($_POST["bgc2"]) :"#ffffff");
$adminemail = getConfiguration("adminemail",($_POST["adminemail"] != "") ? ($_POST["adminemail"]):"");

	echo $htop;
	
	echo "<br>";
	echo "<br>";
	echo "<style>
	legend, h1 {
		padding-top:10px;
		padding-left:180px;
	}
	input, textarea {
	  width: 280px;
	}
	</style>";
	
	echo "<br>";
	echo "<h1>Setup - Step 1 of 2</h1>";
	echo "<br>";
	echo "<div>$setuperror</div>";
	echo "<br>";
	echo "<form action='setup.php' method='POST'>";
	echo "<input type='hidden' name='action' value='setup'>";
	echo "<input type='hidden' name='step' value='2'>";
	echo "<table>";
	echo "<tr><td>Site Name</td><td><input name='sitename' value='$sitename'></td></tr>";

	echo "<tr><td colspan='3'><legend>Colours</legend></td></tr>";
	echo "<tr><td width='180'>Header Background</td><td><input class='colorpicker' id='_bgc1' name='bgc1' value='$bgc1'></td></tr>";
	echo "<tr><td>Page Background</td><td><input name='bgc2' class='colorpicker' id='_bgc2' value='$bgc2'></td></tr>";

	echo "<tr><td colspan='3'><legend>Database & server</legend></td></tr>";
	echo "<tr><td>Hostname</td><td><input name='dbhost' value='$dbhost'></td></tr>";
	echo "<tr><td>username</td><td><input name='username' value='$username'></td></tr>";
	echo "<tr><td>password</td><td><input name='password' value='$password'></td></tr>";
	echo "<tr><td>database</td><td><input name='database' value='$database'></td></tr>";
	echo "<tr><td>images_folder</td><td><input name='images_folder' value='$images_folder'></td></tr>";
	echo "<tr><td>BASEPATH</td><td><input name='BASEPATH' value='$BASEPATH'></td></tr>";
//	"<input name='lang' value='$lang'></td></tr>";
	

	echo "<tr><td colspan='3'><legend>Administration</legend></td></tr>";
	echo "<tr><td>Admin Email address</td><td><input name='adminemail' value='$adminemail'></td></tr>";
	echo "<tr><td>Admin Password</td><td><input name='MASTER_PASSWORD' value='$MASTER_PASSWORD'></td></tr>";
	echo "<tr><td>url</td><td><input name='url' value='$url'></td></tr>";
	echo "<tr><td>Language</td><td><select class='span4' name='lang'>";
	require ("lang/_languages.php");
	foreach ($_languages as $key=>$data) {
		$selected = ($lang == $key) ? "selected" : "";
		echo sprintf("<option value='%s' %s>%s</option>",$key,$selected,$data);
	}
	echo "</select></td></tr>";

	echo "<tr><td> </td><td><input type='submit'></td></tr>";
	echo "</table>";
	echo "</form>";
	echo "<script> $D(function() { $D('#_bgc1').colorpicker({format: 'hex'}); });</script>";
	echo "<script> $D(function() { $D('#_bgc2').colorpicker({format: 'hex'}); });</script>";
		echo $hbot;


?>