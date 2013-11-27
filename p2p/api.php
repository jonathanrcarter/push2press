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

  
session_start();
error_reporting(0);

if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

if ($_GET["action"] != "") $_POST = $_GET;
$action = $_POST["action"];

$browser = $_POST["browser"];
$osn = $_POST["__osn"];				// ipad , android
$pusht = $_POST["__pusht"];			// xxx-xxx-xx-xxx
$dtype = $_POST["__dtype"];			// dev , prod
$aid = $_POST["__aid"];				// com.glimworm.push2press.01
$av = $_POST["__av"];				// 1.0
$aiid = $_POST["__aiid"];			// xxx-xxx-xxx-xxx
$a__name = $_POST["__name"];		// j carter
$a__groups = $_POST["__groups"];	// 1,2,3
$D = "$";
$MSG = "";

$gid = "1";
$_SESSION['KCFINDER'] = array();
$_SESSION['KCFINDER']['disabled'] = false;

if ($browser && $browser != "") {
	$_SESSION['browser'] = $browser;
}

//require_once './lang/en.php';
require_once 'version.php';

function getConfiguration($VAL,$DEFAULT_VAL) {
	global $username,$password,$database,$dbhost;

	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	$query="select * from domain where Pagename = '".$VAL."'";
	$result=mysql_query($query);
	
	if (mysql_numrows($result) > 0) {
		return mysql_result($result,0,"Caption");
	} else {
		return $DEFAULT_VAL;
	}
} 
function setConfiguration($VAL,$DEFAULT_VAL) {
	global $username,$password,$database,$dbhost;
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );

	$val = getConfiguration($VAL,null);
	
	if ($val == null) {
		mysql_query("insert into domain (Pagename,Caption) values('".$VAL."','".$DEFAULT_VAL."')");
	} else {
		mysql_query("update domain set Caption = '".$DEFAULT_VAL."' where Pagename = '".$VAL."'");
	}
}

function esc($S) {
//	echo "<!-- magic : " . get_magic_quotes_gpc() . " -->";
	if (get_magic_quotes_gpc()) return $S;
	return addslashes($S);
	//mysql_real_escape_string($S);
}
$dbhost = "localhost";
$username = "";
$password = "";
$database = "";
$images_folder = "/client_images/";
$BASEPATH = "/";
$MASTER_PASSWORD="";
$lang = "en";


if (file_exists("./local_config.php") == false) {
	file_put_contents("./local_config.php", "<?php\n\n?>");
}
if (file_exists("./local_menu.php") == false) {
	file_put_contents("./local_menu.php", "<?php\n\n?>");
}
if (file_exists("./local_functions.php") == false) {
	file_put_contents("./local_functions.php", "<?php\n\n?>");
}

require './local_config.php';

require_once './lang/'.$lang.'.php';


if ($database == "") {
	echo sprintf(L("runsetup"),"");
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: setup.php"); 
	exit;
}
$db = mysql_connect($dbhost,$username,$password);
if (!$db) {
	echo sprintf(L("runsetup"),"Could not connect to MySql , try a different login");
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: setup.php"); 
	exit;
}
if (!mysql_select_db($database)) {
	echo sprintf(L("runsetup"),"Could not connect to Database");
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: setup.php"); 
	exit;
}

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
function column_exists($tablename, $columnname, $createsql) {
	global $username,$password,$database,$dbhost;
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database);
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
    $res = mysql_query("
    	select count(*) AS count 
    	from information_schema.columns 
        WHERE table_schema = '$database' 
        AND table_name = '$tablename' 
        AND column_name = '$columnname'
     ");
    if (mysql_result($res, 0) == 0) {
    	mysql_query($createsql);
    }
}
function sqlcount($sql) {
	global $username,$password,$database,$dbhost;
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database);
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	$result = mysql_query($sql);
	return mysql_result($result,0,"c");
}
/*
cats
domain
groups
log
log_phone
menu
message
pages
pushmessages
*/
if (!table_exists("log_phone")) {
	echo sprintf(L("runsetup"),"Could not find the database tables");
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: setup.php"); 
	exit;
}

/* auto add column if not exists */
column_exists("cats", "collapse", "alter table cats add column collapse varchar(4) not null default 'n'");
column_exists("cats", "Volgorde", "alter table pages add column Volgorde int(4) not null default 0");

//$bootstrap = '	<link href="http://www.glimworm.com/_assets/moock/bootstrap/css/bootstrap.css" rel="stylesheet">';
//$bootstrap = '	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">';
$bootstrap = '	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.0.4/css/bootstrap-combined.min.css" rel="stylesheet">';

$angular = '	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>';
$backbone = '	<script src="//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.0/backbone-min.js"></script>';


$htoppopup = "";
$htoppopup = $htoppopup .'<!DOCTYPE html>';
$htoppopup = $htoppopup .'<html lang="en">';
$htoppopup = $htoppopup .'  <head>';
$htoppopup = $htoppopup .'    <meta charset="utf-8">';
$htoppopup = $htoppopup .'    <title>Push 2 Press App CMS - '.getConfiguration("sitename","").'</title>';
$htoppopup = $htoppopup .'    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
$htoppopup = $htoppopup .'    <meta name="description" content="">';
$htoppopup = $htoppopup .'    <meta name="author" content="">';
$htoppopup = $htoppopup .'    <!-- Le styles -->';
$htoppopup .= $bootstrap;
$htoppopup .= $angular;
$htoppopup .= $backbone;
$htoppopup = $htoppopup .'	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.css">';
$htoppopup = $htoppopup .'    <link href="http://www.glimworm.com/_assets/moock/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">';
$htoppopup = $htoppopup . "\n";
$htoppopup = $htoppopup .'    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />';
$htoppopup = $htoppopup .'    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>';
$htoppopup = $htoppopup .'    <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>';
$htoppopup = $htoppopup .'  </head>';
$htoppopup = $htoppopup .'  <body onload="loaderfunction()">';

$hbotpopup = "";
$hbotpopup = $hbotpopup .'    <!-- Le javascript';
$hbotpopup = $hbotpopup .'    ================================================== -->';
$hbotpopup = $hbotpopup .'    <!-- Placed at the end of the document so the pages load faster -->';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-transition.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-alert.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-modal.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-dropdown.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-scrollspy.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-tab.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-tooltip.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-popover.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-button.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-collapse.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-carousel.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-typeahead.js"></script>';
$hbotpopup = $hbotpopup .'    <!-- http://www.eyecon.ro/bootstrap-datepicker/ -->';
$hbotpopup = $hbotpopup .'    <link rel="stylesheet" href="http://www.glimworm.com/_assets/moock/bootstrap/extras/datepicker/css/datepicker.css" />';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/extras/datepicker/js/bootstrap-datepicker.js"></script>';
$hbotpopup = $hbotpopup .'    <!-- http://jdewit.github.com/bootstrap-timepicker/ -->';
$hbotpopup = $hbotpopup .'    <link rel="stylesheet" href="http://www.glimworm.com/_assets/moock/bootstrap/extras/bootstrap-timepicker-master/css/bootstrap-timepicker.css" />';
$hbotpopup = $hbotpopup .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/extras/bootstrap-timepicker-master/js/bootstrap-timepicker.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="lib/js/jquery.filterList.min.js"></script>';
$hbotpopup = $hbotpopup .'    <script src="lib/js/jquery-sortable.js"></script>';
$hbotpopup = $hbotpopup .'  </body>';
$hbotpopup = $hbotpopup .'</html>';






$htop = "";
$htop = $htop .'<!DOCTYPE html>';
$htop = $htop .'<html lang="en">';
$htop = $htop .'  <head>';
$htop = $htop .'    <meta charset="utf-8">';
$htop = $htop .'    <title>Push 2 Press App CMS - '.getConfiguration("sitename","").'</title>';
$htop = $htop .'    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
$htop = $htop .'    <meta name="description" content="">';
$htop = $htop .'    <meta name="author" content="">';
$htop = $htop . "<script type='text/javascript' src='ckeditor/ckeditor.js'></script>";
//$htop = $htop .'<script type="text/javascript" src="ckfinder/ckfinder.js"></script>';

$htop = $htop .'    <!-- Le styles -->';
$htop .= $bootstrap;
$htop .= $angular;
$htop .= $backbone;

$htop = $htop .'	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.css">';
$htop = $htop .'    <link href="http://www.glimworm.com/_assets/moock/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">';
$htop = $htop . "\n";

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
$htop = $htop . "\n";

$htop = $htop ."<script type='text/javascript'>";
/*$htop = $htop ."var finder = new CKFinder();";
$htop = $htop ."finder.basePath = '".$BASEPATH."ckfinder/';";
$htop = $htop . "function showFileInfo(a,b,c) {";
$htop = $htop . "alert(a+' / '+b+' / '+c);";
$htop = $htop . "$('#main_img').attr('src',a);";
$htop = $htop . "$('#main_img_fld').val(a);";
$htop = $htop . "";
$htop = $htop . "}";
$htop = $htop ."finder.selectActionFunction = showFileInfo;";
$htop = $htop ."function troller(){";
$htop = $htop ."finder.popup();";
$htop = $htop ."}";*/
$htop = $htop ."";

$htop = $htop . "function troller() {";
$htop = $htop . "    window.KCFinder = {";
$htop = $htop . "        callBack: function(url) {";
$htop = $htop . "            window.KCFinder = null;";
$htop = $htop . "            var img = new Image();";
$htop = $htop . "            img.src = url;";
$htop = $htop . "            img.onload = function() {";
$htop = $htop . "			$('#main_img').attr('src',url);";
$htop = $htop . "			$('#main_img_fld').val(url);";
$htop = $htop . "            }";
$htop = $htop . "        }";
$htop = $htop . "    };";
$htop = $htop ."push2press.popupWin('kcfinder/browse.php?type=images');";
//$htop = $htop . "    window.open('kcfinder/browse.php?type=images','kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, directories=0, resizable=1, scrollbars=0, width=800, height=600');";
$htop = $htop . "}";



$htop = $htop ."</script>";
$htop = $htop . "\n";
$htop = $htop .'<link href="api.css" rel="stylesheet">';
$htop = $htop . "\n";
$htop = $htop .'<script src="api.js"></script>';
$htop = $htop . "\n";

//$htop = $htop .'<script type="text/javascript">';
//$htop = $htop .'function kcnew(){';
//$htop = $htop ."push2press.popupWin('kcfinder/browse.php?type=images');";
//$htop = $htop ."window.open('kcfinder/browse.php?type=images','kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, directories=0, resizable=1, scrollbars=0, width=800, height=600');";
//$htop = $htop .'}';
//$htop = $htop .'</script>';
$htop = $htop . "\n";

$htop = $htop .'  </head>';
$htop = $htop .'  <body>';
/*
$htop = $htop .'    <div id="xx-p2p-topnavbar" class="navbar navbar-fixed-top">';
$htop = $htop .'      <div class="xnavbar-inner">';
$htop = $htop .'        <div class="container">';
$htop = $htop .'          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">';
$htop = $htop .'            <span class="icon-bar"></span>';
$htop = $htop .'            <span class="icon-bar"></span>';
$htop = $htop .'            <span class="icon-bar"></span>';
$htop = $htop .'          </a>';

$htop = $htop .'          <div class="nav-collapse">';

$htop = $htop .'            <ul class="nav nav-top-block" style="background-color : #fff;">';
$htop = $htop .'          	<li class="dropdown" id="menu1"><a class="brand" href="api.php"><img src="images/application-logo.png"></a>';
$htop = $htop .'              </li>';

require_once './local_menu.php';

$htop = $htop .'            </ul>';
$htop = $htop .'          </div><!--/.nav-collapse -->';
$htop = $htop .'        </div>';
$htop = $htop .'      </div>';
$htop = $htop .'    </div>';
*/

$htop = $htop .'    <div class="container">';
$htop .= '<div id="xx-p2p-topnavbar"><a class="brand" href="api.php"><img src="images/back-to-dashboard.png"></a></div>';


$hbot = "";
$hbot .= '      <footer>';
$hbot .= '        <p>&copy; Glimworm IT BV 2013 -  Version : ' . $push2version["major"] . ' ' . $push2version["type"]. ' , Build : ' . $push2version["build"] . '</p>';


$hbot = $hbot .'      </footer>';
$hbot = $hbot .'      </div>';
$hbot = $hbot . '<div class="modal hide fade" id="modal-window"></div>';
$hbot = $hbot . '<div class="modal hide fade" id="modal-window2"></div>';

$hbot = $hbot .'    <!-- Le javascript';
$hbot = $hbot .'    ================================================== -->';
$hbot = $hbot .'    <!-- Placed at the end of the document so the pages load faster -->';
//$hbot = $hbot .'    <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>';
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
$hbot = $hbot .'    <!-- http://www.eyecon.ro/bootstrap-datepicker/ -->';
$hbot = $hbot .'    <link rel="stylesheet" href="http://www.glimworm.com/_assets/moock/bootstrap/extras/datepicker/css/datepicker.css" />';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/extras/datepicker/js/bootstrap-datepicker.js"></script>';
$hbot = $hbot .'    <!-- http://jdewit.github.com/bootstrap-timepicker/ -->';
$hbot = $hbot .'    <link rel="stylesheet" href="http://www.glimworm.com/_assets/moock/bootstrap/extras/bootstrap-timepicker-master/css/bootstrap-timepicker.css" />';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/extras/bootstrap-timepicker-master/js/bootstrap-timepicker.js"></script>';
$hbot = $hbot .'    <script src="lib/js/jquery.filterList.min.js"></script>';
$hbot = $hbot .'    <script src="lib/js/jquery-sortable.js"></script>';
$hbot = $hbot .'  </body>';
$hbot = $hbot .'</html>';

function B($TYPE, $LINK) {

	switch ($TYPE) {
	    case "send":
	    	return 	"<a class='btn btn-small btn-info' href='" . $LINK . "'><i class='icon-edit icon-black'></i> ".L("SEND")."</a>";
	        break;
	    case "edit":
	    	return 	"<a class='btn btn-small btn-info' href='" . $LINK . "'><i class='icon-edit icon-black'></i> ".L("EDIT")."</a>";
	        break;
	    case "edit-txt":
	    	return 	"<span class='x-phone-btn-grey'><a href='" . $LINK . "'>".L("EDIT")."</a></span>";
	        break;
	    case "preview":
	    	return 	"<a class='btn btn-small btn-info' href='" . $LINK . "'><i class='icon-share icon-black'></i> ".L("PREVIEW")."</a>";
	        break;
	    case "preview-txt":
	    	return 	"<span class='x-phone-btn-grey'><a href='" . $LINK . "'> ".L("PREVIEW")."</a></span>";
	        break;
	    case "compose":
	    	return 	"<a class='btn btn-small btn-info' href='" . $LINK . "'><i class='icon-comment icon-white'></i> ".L("COMPOSE")."</a>";
	        break;
	    case "S&S";
	    	return "<a class='btn btn-small btn-info' href='" . $LINK . "'><i class='icon-comment icon-black'></i> ".L("SS")."</a>";
	    	break;    
	    case "delete_submit":
	    	return 	"<input type='submit' class='btn btn-small btn-danger' value='delete'>";
	        break;
	    case "delete":
	    	return 	"<a class='btn btn-small btn-info' href='" . $LINK . "'><i class='icon-trash icon-black'></i> ".L("DELETE")."</a>";
//	    	return 	"<a class='btn btn-mini btn-danger' href='" . $LINK . "'><i class='icon-trash icon-white'></i> ".L("DELETE")."</a>";
	        break;
	        	    	
	}

}


function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

class obj {
}

function log2($MSG,$STATUS) {
	global $username,$password,$database,$dbhost;
	
	$db = mysql_connect($dbhost,$username,$password);
       	mysql_select_db($database) or die("Unable to select database");
       	mysql_query("SET NAMES utf8", $db);
       	mysql_query( "SET CHARACTER SET utf8", $db );
       	$query="insert ignore into log values(0,now(),'".$MSG."','".$STATUS."')";
       	$result=mysql_query($query);
}

function log3($osn, $pusht, $dtype, $aid, $gid) {
	global $username,$password,$database,$dbhost;
	
	$db = mysql_connect($dbhost,$username,$password);
   	mysql_select_db($database) or die("Unable to select database");
   	mysql_query("SET NAMES utf8", $db);
   	mysql_query( "SET CHARACTER SET utf8", $db );
   	$query="insert into log_phone (id,uid,gid,dtype,osn,aid,ts) values (0,'$pusht','$gid','$dtype','$osn','$aid',now()) on duplicate  key update dtype='$dtype', aid='$aid', osn='$osn', ts=now()";
   	$result=mysql_query($query);
	
}

function log4($pusht, $name, $groups) {
	global $username,$password,$database,$dbhost;
	
	if ($name == "" && $groups == "") return;
	
	$db = mysql_connect($dbhost,$username,$password);
   	mysql_select_db($database) or die("Unable to select database");
   	mysql_query("SET NAMES utf8", $db);
   	mysql_query( "SET CHARACTER SET utf8", $db );
   	$query="update ignore log_phone set name='".mysql_escape_string($name)."',groups='".$groups."' where uid='".$pusht."'";
   	$result=mysql_query($query);
       	
	return $query;
	
}



function textareaSafe($TEXT) {
	$TEXT =  str_replace(">", "&gt;" , $TEXT);
	$TEXT =  str_replace("<", "&lt;" , $TEXT);
	return $TEXT;
}

if ( $action == "info" ) {
	phpinfo();
	exit();
}

if ( $action == "logout" ) {
        $_SESSION['p2p_password'] = "";
        $action = "";
}

if ( $action == "login" ) {
        $pwd = $_POST["pwd"];
        $_SESSION['p2p_password'] = $pwd;
        $action = "";
}

if ( $action == "get-page-raw" ) {
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	$id = $_POST["id"];
	$query="select * from pages where id=" . $id;
	$result=mysql_query($query);
	echo mysql_result($result,$r,"bodytext");
	
	$query666="insert ignore into stats (id,ts,action) values (0,now(),'get-page-raw&id=$id')";
    $result666=mysql_query($query666);
    
	exit;

} else if ( $action == "get-msg-raw" ) {
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	$id = $_POST["id"];
	$query="select * from message where id=" . $id;
	$result=mysql_query($query);
	$bodytext = mysql_result($result,$r,"bodytext");
	

	$merge = $_POST["merge"];		// y or n
	
	if ($merge && $merge == "y") {
		$template = "";
		if ($template == "") $template = "msg_template";

		$content = "";
		
		try {
			error_reporting(0);
			$content = file_get_contents("templates/messages/".$template. ".html");
		} catch (Exception $e) {
			$content = "";
		}
		if (!$content || $content == null || $content == "") {
			$content = "<html><head></head><body>{content}</body></html>";
		}

		$content =  str_replace("{content}", $bodytext, $content);
	
		echo $content;
	} else {
		echo $bodytext;
	}
	
	$query666="insert ignore into stats (id,ts,action) values (0,now(),'get-msg-raw&id=$id')";
    $result666=mysql_query($query666);
	
	exit;

} else if ( $action == "update-token" ) {

	log2("get-struct osn=$osn pusht=$pusht dtype=$dtype aid=$aid","");
	log3($osn, $pusht, $dtype, $aid, $gid);
	
	$query666="insert ignore into stats (id,ts,action) values (0,now(),'update-token')";
    $result666=mysql_query($query666);
	

}else if ( $action == "register-device" ) {

	log2("get-struct osn=$osn pusht=$pusht dtype=$dtype aid=$aid name=$a__name groups=$a__groups","");
	log3($osn, $pusht, $dtype, $aid, $gid);
	
	$query666="insert ignore into stats (id,ts,action) values (0,now(),'register-device')";
    $result666=mysql_query($query666);

	$l4 = log4($pusht, $name, $groups);

    $retval = new obj();
    $retval->status = 0;
    $retval->statusMsg = sprintf("successfully updated pusht[%s]",$pusht);
    $retval->l4 = $l4;
    echo json_encode($retval);
	
	exit;
	
} else if ( $action == "register-device-update-personal-data") {

	log2("register-device-update-personal-data osn=$osn pusht=$pusht dtype=$dtype aid=$aid","");
	log3($osn, $pusht, $dtype, $aid, $gid);
	$name = $_POST["name"];
	$groups = $_POST["groups"];
	$l4 = log4($pusht, $name, $groups);

    $retval = new obj();
    $retval->status = 0;
    $retval->statusMsg = sprintf("successfully updated pusht[%s] name[%s] groups[%s]",$pusht,$name,$groups);
    $retval->l4 = $l4;
    echo json_encode($retval);
	
	exit;


}else if ( $action == "get-struct" ) {

	log2("get-struct osn=$osn pusht=$pusht dtype=$dtype aid=$aid","");
	log3($osn, $pusht, $dtype, $aid, $gid);


	$timthumb = getConfiguration("url","http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
	$timthumb = str_replace("api.php", "", $timthumb);


	
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );

	$query="select * from cats order by Volgorde";
	$result=mysql_query($query);
	
    $retval = new obj();
	$retval->cats = array();
	$retval->config = array();
	$retval->config2 = array();
	$retval->groups = array();
	$retval->site = new obj();
	$retval->user = array();
	
	for ($r=0; $r < mysql_numrows($result); $r++) {

		$img = mysql_result($result,$r,"img");
		$img_size = ($img && $img != "") ? sprintf($timthumb."/timthumb.php?h=32&w=32&src=%s",$img) : "";
		if (strpos($img_size,"client_images/images/icons/glyphicons")) $img_size = $img_size . "&zc=2&f=5,238,238,238,1";
		
		$cat = new obj();
		$cat->id = mysql_result($result,$r,"id");
		$cat->Pagename = mysql_result($result,$r,"Pagename");
		$cat->collapse = mysql_result($result,$r,"collapse");
		$cat->img = $img_size;
		$cat->imgv2 = $img_size;
		$cat->pages = array();

		$caption = mysql_result($result,$r,"Caption");

		
		if (startsWith($caption, "atom:")) {
			$testXmlFile = "atom_cat_".$cat->id.".xml";
	    	$captionParts = explode(":", $caption,2);
			
//			$download = file_put_contents($testXmlFile, file_get_contents("http://www.parkshark.eu/feed/atom/"));
			$download = file_put_contents($testXmlFile, file_get_contents($captionParts[1]));
			$xml2 = simplexml_load_file($testXmlFile,'SimpleXMLElement', LIBXML_NOCDATA);
			for ($i=0; $i < count($xml2->entry); $i++) {
				$page = new obj();
				$page->id = (1000*$cat->id)+$i;
				$page->Pagename = substr(sprintf("%s",$xml2->entry[$i]->title),0,30);
				$page->type = "atom:".$testXmlFile.":".$i;
				$page->extraData = '{ "navBar" : "y" }';
				array_push($cat->pages,$page);
			}
		} else {
			
			$query2="select * from pages where CatID = " . $cat->id ." order by Volgorde";
			$result2=mysql_query($query2);
			for ($r2=0; $r2 < mysql_numrows($result2); $r2++) {
			
				$img = mysql_result($result2,$r2,"img");
				$img_size = ($img && $img != "") ? sprintf($timthumb."/timthumb.php?h=32&w=32&src=%s",$img) : "";
				if (strpos($img_size,"client_images/images/icons/glyphicons")) $img_size = $img_size . "&zc=2&f=5,238,238,238,1";
			
				$page = new obj();
				$page->id = mysql_result($result2,$r2,"id");
				$page->Pagename = mysql_result($result2,$r2,"Pagename");
				$page->type = mysql_result($result2,$r2,"Type");
				$page->extraData = mysql_result($result2,$r2,"extraData");
				$page->img = $img_size;
				$bt = null;
				
				if ($page->type && $page->type == "QC") {
					if ($bt == null) $bt = mysql_result($result2,$r2,"bodytext");
					
					$bt2 = "";
					$bt2 = $bt2. "<!--|\n";
					$bt2 = $bt2 . "var getlines = function() {\n";
					$bt2 = $bt2 . " var lines = [];\n";
					
					$btlines = explode("\n",$bt);
					foreach ($btlines as $btline) {
						$bt2 = $bt2 . sprintf('lines.push("%s");',substr($btline,0,-1)). "\n";
					}
					$bt2 = $bt2 . " return lines;\n";
					$bt2 = $bt2 . "}\n";
					$bt2 = $bt2 . file_get_contents("plugins/ticode/quickcontent.js");
					$bt2 = $bt2. "|--> \n";
					
					$page->type = "TI";
					$bt = $bt2;
				
				}
				
				if ($page->type && $page->type == "TI") {
					if ($bt == null) $bt = mysql_result($result2,$r2,"bodytext");
					$bt = explode("|",$bt);
					if (count($bt) > 1) {
						$bt1 = $bt[1];
						while (strpos($bt1,"@include(")) {
							$bt2 = explode("\n",$bt1);
							$bt1 = "";
							foreach ($bt2 as $btline) {
								$start = strpos($btline,"@include(");
								if ($start > -1) {
									$end = strpos($btline,")");
									if ($end > $start) {
										$script = substr($btline,$start+10,($end - ($start+11)));
										$btline = 
										$btline = "/* included(".$script.") */\n" . file_get_contents("plugins/ticode/".$script);
									} else {
										$btline = "";
									}
								}
								$bt1 = $bt1 . $btline . "\n";
							}
						
						}
						$page->bt = $bt1;
					}
				}
				array_push($cat->pages,$page);
			}
		}

		array_push($retval->cats,$cat);		
	}
	
	$query1="select * from domain";
	$result1=mysql_query($query1);
	for ($r=0; $r < mysql_numrows($result1); $r++) {
	
		$config = new obj();
		$config->id = mysql_result($result1,$r,"id");
		$config->_key = mysql_result($result1,$r,"Pagename");
		$config->_value = mysql_result($result1,$r,"Caption");
		array_push($retval->config,$config);
		
		$retval->config2[mysql_result($result1,$r,"Pagename")] =  mysql_result($result1,$r,"Caption");

	}

	$query="select * from groups order by gid desc limit 50";
    $result=mysql_query($query);
	for ($r=0; $r < mysql_numrows($result); $r++) {
		$grp = new obj();
		$grp->gid = mysql_result($result,$r,"gid");
		$grp->gname = mysql_result($result,$r,"gname");
		array_push($retval->groups,$grp);
	}

	$query="select * from log_phone where uid='$pusht'";
    $result=mysql_query($query);
	for ($r=0; $r < mysql_numrows($result); $r++) {
		$user = new obj();
		$user->name = mysql_result($result,$r,"name");
		$user->groups = mysql_result($result,$r,"groups");
		array_push($retval->user,$user);
	}


	$query666="insert ignore into stats (id,ts,action) values (0,now(),'get-struct')";
    $result666=mysql_query($query666);
    
    $retval->site->name = getConfiguration("sitename","");
	
    echo json_encode($retval);
	exit;
} 

else if ( $action == "get-mes" ) {
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query("SET CHARACTER SET utf8", $db );
	
    $retval = new obj();
	$retval->items = array();
	
	$query2="select * from message where status='sent' order by ts_sent desc, id desc";
	$result2=mysql_query($query2);
	for ($r2=0; $r2 < mysql_numrows($result2); $r2++) {
		$page = new obj();
		$page->id = mysql_result($result2,$r2,"id");
		$page->Pagename = mysql_result($result2,$r2,"Pagename");
		$page->Caption = mysql_result($result2,$r2,"Caption");
		$page->img = mysql_result($result2,$r2,"img");
		$page->bodytext = mysql_result($result2,$r2,"bodytext");
		$page->Volgorde = mysql_result($result2,$r2,"Volgorde");
			
		array_push($retval->items,$page);
	}
	
	$query666="insert ignore into stats (id,ts,action) values (0,now(),'get-mes')";
    $result666=mysql_query($query666);
	
    echo json_encode($retval);
	exit;
}
else if ( $action == "get-mes-html" ) {
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query("SET CHARACTER SET utf8", $db );
	
    $retval = new obj();
	$retval->items = array();
	
	$id = $_POST["id"];
	$query2="select * from message where id=" . $id;
	$result2=mysql_query($query2);
	
	$query666="insert ignore into stats (id,ts,action) values (0,now(),'get-mes-html&id=$id')";
    $result666=mysql_query($query666);
	
	echo mysql_result($result2,0,"bodytext");
	exit;
}
else if ( $action == "get-page" ) {
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	$id = $_POST["id"];
	$query="select * from pages where id=" . $id;
	$result=mysql_query($query);
	
	$template = mysql_result($result,$r,"template");
	if ($template == "") $template = "template";

	$content = file_get_contents("templates/pages/".$template. ".html");
	
	$type = mysql_result($result,$r,"type");
	
	$atomindex = 0;
	$atomcat = 0;
	
	if ($id > 999) {
		$type = sprintf("atom:atom_cat_%s.xml:%s",floor($id/1000),($id%1000));
		$atomcat = floor($id/1000);
		$atomindex = ($id%1000);
	}	
	
	
	if (startswith($type, "atom:")) {

    	$types = explode(":", $type);
		$testXmlFile = $types[1];
		$i = $atomindex;
		$xml2 = simplexml_load_file($testXmlFile,'SimpleXMLElement', LIBXML_NOCDATA);
		$content =  str_replace("{content}", sprintf("%s",$xml2->entry[$i]->content), $content);
		$content =  str_replace("{pagename}", sprintf("%s",$xml2->entry[$i]->title), $content);
	
	} else if (startswith($type, "wp:")) {

    	$types = explode(":", $type);
		$wpId = $types[1];
        $h = "<h1>wordpress id ". $wpId . "</h1>";

        // $wpId = mysql_result($result,$r,"bodytext");
        // $wpId = str_replace('[wp:', '', $wpId);
        // $wpId = str_replace(']', '', $wpId);
        // $wpId = str_replace('<p>', '', $wpId);
        // $wpId = str_replace('</p>', '', $wpId);
        // $wpId = preg_replace("/[^0-9]/", "", $wpId);
        $query666 = "select * from wp_posts where ID='".$wpId."'";
        $result666 = mysql_query($query666, $db);
		
       
        if (mysql_numrows($result666) > 0) {
        	$cols = array("ID","post_author","post_date","post_date_gmt","post_content","post_title","post_excerpt","post_status","comment_status","ping_status","post_password","post_name","to_ping","pinged","post_modified","post_modified_gmt","post_content_filtered","post_parent","guid","menu_order","post_type","post_mime_type","comment_count");
			foreach ($cols as $col) {
		        $content =  str_replace("{".$col."}", mysql_result($result666,0,$col), $content);
			}
//            $thing = mysql_result($result666,0,"post_content");
//            $h = $h ."<p>".$thing."</p>";
        }
		
//        $content =  str_replace("{content}", $h, $content);
        $content =  str_replace("{pagename}", mysql_result($result,$r,"Pagename"), $content);
		
	} else if ($type == "leraar") {

		$h = "";
		$srch = $_POST['srch'];

		$current_menucat = "";
		
		$h = $h . "<form action='api.php'>";
        $h = $h . "<input type='hidden' name='action' value='get-page'>";
        $h = $h . "<input type='hidden' name='id' value='2'>";
        $h = $h . "<div id='textbox'>";
        $h = $h . "<input type='text' name='srch' id='textboxinner' onfocus='changetxt()' value='Klik hier om te zoeken'";
        $h = $h . "</div>";
        $h = $h . "</form>";
		
		if ( $srch == "" || $srch == " ") {
			$query4 = "select * from menu order by id";
		} else{
			$query4 = "select * from menu where Pagename like '%".$srch."%' OR menuCat like '%".$srch."%' OR Caption like '%".$srch."%' order by id";
		}
		
		$result4=mysql_query($query4);
	
		$h = $h . "<table width='100%'>";
		
		for ($r4=0; $r4 < mysql_numrows($result4); $r4++) {
			$this_menucat = mysql_result($result4,$r4,"menuCat");
			if ($this_menucat != $current_menucat) {
				$h = $h . "<tr><td colspan='9'>";
				$h = $h . "<h3 class='catsMenu'><b>".$this_menucat."</b></h3>";
				$h = $h . "<hr>";
				$h = $h . "</td></tr>";
				$current_menucat = $this_menucat;
			}
			
			$img = mysql_result($result4,$r4,"img");
			if ($img != "") {
				$img = "<img src='" . mysql_result($result4,$r4,"img") . "' width='100' height='133'>";
			} else {
				$img = "&nbsp;";
			}
			
			$h = $h . "<tr class='lastone'>";
			$h = $h . "<td>".$img."</td>";
			$h = $h . "<td width='15'></td>";
			$h = $h . "<td><div class='rest_title'><p class='pagename'>" . mysql_result($result4,$r4,"Pagename") . "</p></div><div class='rest_caption'><p>" . mysql_result($result4,$r4,"Caption") . "</p></div></td>";
			$h = $h . "</tr>";
			$h = $h . "<tr>";
			$h = $h . "<td colspan='3'><p class='price'>". mysql_result($result4,$r4,"text") . "</p></td>";
			$h = $h . "</tr>";$h = $h . "<tr>";
			$h = $h . "<td colspan='2'><p> </p></td>";
			$h = $h . "</tr>";
		}
		$h = $h . "</table>";
		$content =  str_replace("{content}", $h, $content);
		$content =  str_replace("{pagename}", mysql_result($result,$r,"Pagename"), $content);
	
	}else if ($type == "Rooster") {
		
		$h = "";
		$srch = $_POST['srch'];

		$current_menucat = "";
		
		$h = $h . "<form action='api.php'>";
        $h = $h . "<input type='hidden' name='action' value='get-page'>";
        $h = $h . "<input type='hidden' name='id' value='1'>";
        $h = $h . "<div id='textbox'>";
        $h = $h . "<input type='text' name='srch' id='textboxinner' onfocus='changetxt()' value='Klik hier om te zoeken'";
        $h = $h . "</div>";
        $h = $h . "</form>";
		
		if ( $srch == "" || $srch == " "){
			$query4 = "select * from timetable order by volgorde";
		} else{
			$query4 = "select * from timetable where schoolName like '%".$srch."%' OR className like '%".$srch."%' order by volgorde";
		}
		
		$result4=mysql_query($query4);
		
		$h = $h . "<table width='100%'>";
		
		for ($r4=0; $r4 < mysql_numrows($result4); $r4++) {
			$this_menucat = mysql_result($result4,$r4,"schoolName");
			if ($this_menucat != $current_menucat) {
				$h = $h . "<tr><td colspan='9'>";
				$h = $h . "<h3 class='catsMenu'><b>".$this_menucat."</b></h3>";
				$h = $h . "<hr>";
				$h = $h . "</td></tr>";
				$current_menucat = $this_menucat;
			}
			
			$h = $h . "<tr class='lastone'>";
			$h = $h . "<td><div class='rest_title'><p class='pagename'>" . mysql_result($result4,$r4,"className") . "</p></div></td>";
			$h = $h . "</tr>";
			$h = $h . "<tr>";
			$h = $h . "<td colspan='3'><p class='price'><a href='".mysql_result($result4,$r4,"fileLocation")."'>Klik hier om het rooster te bekijken</a></p></td>";
			$h = $h . "</tr>";
		}
		$h = $h . "</table>";
		$content =  str_replace("{content}", $h, $content);
		$content =  str_replace("{pagename}", mysql_result($result,$r,"Pagename"), $content);
		
	} else {
		$content =  str_replace("{content}", mysql_result($result,$r,"bodytext"), $content);
		$content =  str_replace("{pagename}", mysql_result($result,$r,"Pagename"), $content);
	}
	
	$content =  str_replace("{telephone_number}", getConfiguration("telephone_number","") , $content);
	$content =  str_replace("{longitude}", getConfiguration("longitude","") , $content);
	$content =  str_replace("{latitude}", getConfiguration("latitude","") , $content);
	$content =  str_replace("{email}", getConfiguration("email","") , $content);
	$content =  str_replace("{twit}", getConfiguration("twitterAccount","") , $content);
	$content =  str_replace("{bgc1}", getConfiguration("bgc1","#000000") , $content);
	$content =  str_replace("{bgc2}", getConfiguration("bgc2","#ffffff") , $content);
		
	
	$CatID = mysql_result($result,$r,"CatID");
	
    $query="select * from cats";
    $result=mysql_query($query);
	$h = "<ul>";
	$cnt = 0;
    for ($r=0; $r < mysql_numrows($result); $r++) {
    
		$query1="select * from pages where CatID = " . mysql_result($result,$r,"id") . " limit 1" ;
		$result1=mysql_query($query1);
		if (mysql_numrows($result1) > 0) {
			if ($_SESSION['browser'] != "app") {
				$h = $h . "<li class='cat'><a href='api.php?action=get-page&id=".mysql_result($result1,0,"id")."'>". mysql_result($result,$r,"Pagename") . "</a></li>";
				$cnt++;
			}
			if ($CatID == mysql_result($result1,0,"id")) {
				//echo "a".$CatID;
				$query2="select * from pages where CatID = " . $CatID ;
				$result2=mysql_query($query2);
				//echo $query2;
				for ($r2=0; $r2 < mysql_numrows($result2); $r2++) {
					$h = $h . "<li class='page'><a href='api.php?action=get-page&id=".mysql_result($result2,$r2,"id")."'> ... ". mysql_result($result2,$r2,"Pagename") . "</a></li>";
					$cnt++;
				}
			}
		}
	}
	
	if ($cnt == 0) $all = "";
	
	$query666="insert ignore into stats (id,ts,action) values (0,now(),'get-page&id=$id')";
    $result666=mysql_query($query666);
	
	echo str_replace("{sitemap}", $h, $content);
	exit;

}

if ($_SESSION['p2p_password'] != $MASTER_PASSWORD) {
        $h = "";
        $h = $h . "<form action='api.php' class='form-inline'>";
        $h = $h . "<input type='hidden' name='action' value='login'>";
		$h = $h . "<legend>".L("Login")."</legend>";
        $h = $h . "<input type='password' name='pwd' placeholder='".L("Password")."'><button class='btn' type='submit'>".L("Login")."</button>";
        $h = $h . "</form>";
        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;
        exit;
}

if ( $action == "ping" ) {
        $retval = new obj();
        $retval->status = 0;
        $retval->status_msg = "pinged!!";
        $retval->data = new obj();

        echo json_encode($retval);

} else if ( $action == "instruct" ) {
		
		$h = "";
        $h = $h . "<div class='plain-hero-unit'>";
		$h = $h . "<legend>".L('Instructions')."</legend>";
		$h = $h . "<p>You are using : Version : " . $push2version["major"] . " " . $push2version["type"] . ", build : " . $push2version["build"] . "</p>";
		$h = $h . "</div>";

        echo $htop;
        echo $h;
        echo $hbot;
		
}
else if ( $action == "list-log" ) {
		
		$h = "";
		
		$h = $h . '<link href="api-menu.css" rel="stylesheet">';   
		
		$db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

		$msgpusht = $_POST["msgpusht"];
		$osn = $_POST["osn"];

		$action2 = $_POST["action2"];
		$PAGE = $_POST['p'];
		
		if ($PAGE == "" || $PAGE == "0"){
			$PAGE = "1";
		}
		
		$PAGER = $PAGE-1;
		$LIM1= $PAGER*20;
		$query="select * from log_phone where uid != '' order by id desc limit ".$LIM1.",20";
		$result=mysql_query($query);
		
        $query1="select * from log_phone where uid != '' order by id";
        $result1=mysql_query($query1);
		
		$device_count = 0;
		

		
		$h = $h . "<div class='plain-hero-unit'>";
		
		$h = $h . "<div class='span12'><legend class='x-dash-h2'>".L('send-push-title')."</div>";//<span style='float:right;'><a href='/' class='btn btn-info'>Back to dashboard</a></legend></span></div>";
		$h = $h . "<div class='span5'>";
			
			//list
			//$h =$h . "<p>Below you will see a list of users, you can send them a push notification by clicking on the 'send' button.</p>";
			$h = $h . "<h2 class='x-dash-h2-small'>Devices</h2>";
			
			$h = $h . "<table class='' style='width:100%;'>";
       	 	$h = $h . "<tr>";
       	 	$h = $h . "<th style='border-bottom:#ccc 1px solid;padding-right:16px;text-transform:capitalize;text-align:left;'>Type</th>";
        	$h = $h . "<th style='border-bottom:#ccc 1px solid;padding-right:16px;text-transform:capitalize;text-align:left;'>".L('username')."</th>";
        	$h = $h . "<th style='border-bottom:#ccc 1px solid;padding-right:16px;text-align:left;'>".L('group-id')."</th>";
        	$h = $h . "<th style='border-bottom:#ccc 1px solid;'></th>";//.L('options')."</th>";
        	$h = $h . "</tr>";

			$device_count = mysql_numrows($result);

        	for ($r=0; $r < mysql_numrows($result); $r++) {
                $h = $h . "<tr>";
                //$h = $h . "<td>" . mysql_result($result,$r,"ts") . "</td>";
                $h = $h . "<td style='border-bottom:#eee 1px solid;padding-right:16px;'><img alt='iPhone' title='iPhone' src='images/apple.png' height='15'></td><td style='border-bottom:#eee 1px solid;padding-right:16px;'>" . mysql_result($result,$r,"name") . "</td>";
                
                $gid123 = mysql_result($result,$r,"groups");
                $query6="select * from groups where gid in(0,".$gid123.")";
        		$result6=mysql_query($query6);
				$h = $h . "<td style='border-bottom:#eee 1px solid;padding-right:16px;'>";
				if ($result6) {
	                for ($r6 = 0; $r6 < mysql_numrows($result6); $r6++) {
		                $h = $h . mysql_result($result6,$r6,"gname") . "<br>";
	                }
	            }
				$h = $h . "</td>";
                $h = $h . "<td style='border-bottom:#eee 1px solid;'>";//<a class='btn btn-mini btn-success' href='api.php?action=show-log&id=" . mysql_result($result,$r,"id") . "'><i class='icon-edit icon-white'></i> ".L("EDIT")."</a>";
                $h = $h . B("compose","javascript:\$push2press.push3(".mysql_result($result,$r,"id").", \"\", \"\", \"\");'");
               	$h = $h . "</td>";
                $h = $h . "</tr>";

        	}
        	//$h = $h . "</table>";
        	$h = $h . "<tr>";
        	$h = $h . "<td colspan='3'><br><h2 class='x-dash-h2-small'>Groups</h2></td>";
        	$h = $h . "</tr>";
        	$query9="select * from groups order by gid desc limit 50";
        	$result9=mysql_query($query9);
			
			//$h = $h . "<table class=''>";
       	 	$h = $h . "<tr>";
        	//$h = $h . "<th style='border-bottom:#ccc 1px solid;padding-right:16px;text-transform:capitalize;'>".L('username')."</th>";
        	$h = $h . "<th style='border-bottom:#ccc 1px solid;padding-right:16px;text-align:left;' colspan='2'>Name</th>";
        	$h = $h . "<th style='border-bottom:#ccc 1px solid;'></th>";//.L('options')."</th>";
        	$h = $h . "<th style='border-bottom:#ccc 1px solid;'></th>";
        
        	$h = $h . "</tr>";

        	for ($r=0; $r < mysql_numrows($result9); $r++) {
                $h = $h . "<tr>";
                //$h = $h . "<td>" . mysql_result($result,$r,"ts") . "</td>";
                //$h = $h . "<td style='border-bottom:#eee 1px solid;padding-right:16px;'>" . mysql_result($result9,$r,"gid") . "</td>";
                $h = $h . "<td style='border-bottom:#eee 1px solid;padding-right:16px;' colspan='2'>";
				$h = $h . mysql_result($result9,$r,"gname") . "<br>";
	            $h = $h . "</td>";
                $h = $h . "<td style='border-bottom:#eee 1px solid;text-align:right;' nowrap>";//<a class='btn btn-mini btn-success' href='api.php?action=show-log&id=" . mysql_result($result,$r,"id") . "'><i class='icon-edit icon-white'></i> ".L("EDIT")."</a>";
                $h = $h . "<a href='api.php?action=show-group&id=" . mysql_result($result9,$r,"gid") . "' class='btn btn-small'><i class='icon-edit icon-black'></i> CHANGE NAME</a>&nbsp;&nbsp;</td>";
                $h = $h . "<td style='border-bottom:#eee 1px solid;'>";//<a class='btn btn-mini btn-success' href='api.php?action=show-log&id=" . mysql_result($result,$r,"id") . "'><i class='icon-edit icon-white'></i> ".L("EDIT")."</a>";
                $h = $h . B("compose","javascript:\$push2press.push2(".mysql_result($result9,$r,"gid").", \"\", \"\", \"\");'");
               	$h = $h . "</td>";
               	$h = $h . "</tr>";

        	}
        	$h = $h . "</table>";
				
		$h = $h . "</div>";
		
		$h = $h . "<div class='span6'>";
		
			//messages and compose
			
			 $h = $h . '		<div class="x-dash-imp-grey-div">';
   	 		// $h = $h . '			<img src="images/p2p-icons-black/send-pusher.png" class="x-dash-imp-grey-img">';
     		 $h = $h . '			<div class="x-dash-imp-grey-txt-1" style="float:none !important;">';
     		 $h = $h . '				<h3 class="x-dash-imp-grey-title" style="'.$color100.'">Instructions</h3>';
     		 $h = $h . '				<p class="x-dash-imp-grey-text">To the left you will see a list of registered devices, you can send them a push notification by clicking on the "send" button. In the pop-up enter the text you want to send in the first field and click "send". Additionally you can add a note to yourself so you can describe your push notification for refference.</p>';
     		 $h = $h . '				<p style="text-align:center;"><br><img src="images/push-instructions.png"></p>';
     		  $h = $h . '			</div>';
     		 /*$h = $h . '				<p class="x-dash-imp-grey-text">'.L('send-push-desc-group').'</p>';
     		 $h = $h . '			</div>';
     		 $h = $h . '			<div class="x-dash-imp-grey-buttons">';
     		 $h = $h . '				<a class="x-dash-imp-grey-button-double" style="'.$bgcolor100.'" href="?action=list-log">TRY NOW!</a>';
     		 $h = $h . '			</div>';*/
     		 $h = $h . '		</div>';
			
		
		$h = $h . "</div>";
		
		$h = $h . "</div>";
		$h = $h . "</div>";
		
		
        echo $htop;
        if ($device_count == 0) {
			require_once("api-newsite.php");
	        echo $hh;
        }
        echo $h;
        echo $hbot;
        exit;
}
else if ( $action == "list-log-old" ) {
		
		$h = "";
        $h = $h . "<div class='plain-hero-unit'>";
		$h = $h . "";
		$db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

		$msgpusht = $_POST["msgpusht"];
		$osn = $_POST["osn"];

		$action2 = $_POST["action2"];
		$PAGE = $_POST['p'];
		
		if ($PAGE == "" || $PAGE == "0"){
			$PAGE = "1";
		}
		
		$PAGER = $PAGE-1;
		$LIM1= $PAGER*20;
		$query="select * from log_phone where uid != '' order by id desc limit ".$LIM1.",20";
		$result=mysql_query($query);
		
        $query1="select * from log_phone where uid != '' order by id";
        $result1=mysql_query($query1);
		
        
        
        
        
        $h = $h . "<legend>".L('log_phone')."</legend>";
        $h = $h . "<div class='btn-toolbar'>";
    	
    	$h = $h . "<div class='btn-group'  data-toggle='buttons-radio'>";
        $h = $h . "  <button onClick='javascript:' class='btn $f1active' style='visibility:hidden;'>".L("Check_devices")."</button>";
    
        $h = $h . "</div>";		// end buttongroup
    
        $h = $h . "<div class='btn-group pull-right'>";
        //$h = $h . "<a class='btn btn-success' href='api.php?action=list-log&action2=add'> <i class='icon-plus icon-white'></i> ADD</a>";
        $h = $h . "</div>";		// end buttongroup
        
        $h = $h . "</div>";		// end toolbar
        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        $h = $h . "<th>".L('timestamp')."</th>";
        $h = $h . "<th>".L('group-id')."</th>";
        $h = $h . "<th>".L('username')."</th>";
        $h = $h . "<th>".L('osn')."</th>";
        $h = $h . "<th>".L('osn')."</th>";
        $h = $h . "<th>".L('username')."</th>";
        $h = $h . "<th>".L('options')."</th>";
        
        $h = $h . "</tr>";

        for ($r=0; $r < mysql_numrows($result); $r++) {
                $h = $h . "<tr>";
                $h = $h . "<td>" . mysql_result($result,$r,"ts") . "</td>";
                
                $gid123 = mysql_result($result,$r,"groups");
                $query6="select * from groups where gid in(0,".$gid123.")";
        		$result6=mysql_query($query6);
				$h = $h . "<td>";
				if ($result6) {
	                for ($r6 = 0; $r6 < mysql_numrows($result6); $r6++) {
		                $h = $h . mysql_result($result6,$r6,"gname") . "<br>";
	                }
	            }
				$h = $h . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"name") . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"aid") . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"dtype") . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"un") . "</td>";
                $h = $h . "<td>";
                //$h = $h . "<td><a class='btn btn-mini btn-info' href='api.php?action=show-log&id=" . mysql_result($result,$r,"id") . "'><i class='icon-edit icon-white'></i> ".L("EDIT")."</a>";
                //$h = $h . " ";
    			
                $h = $h . B("compose","javascript:\$push2press.push3(".mysql_result($result,$r,"id").", \"\", \"\", \"\");'");
                //$h = $h . " ";
                //$h = $h . "" . B("delete","api.php?action=del-log&id=" . mysql_result($result,$r,"id")) . "</td>";
                $h = $h . "</td>";
                $h = $h . "</tr>";

        }
        $h = $h . "</table>";
         
         $NP = $PAGE-1;
	 	 $NP1 = $PAGE+1;	
 
         $h = $h . '<div class="pagination pagination-small pagination-centered">';
 		 $h = $h . '	<ul>';
 		 $h = $h . ' 		<li><a href="api.php?action=list-log&p='.max($NP,1).'">&laquo;</a></li>';

		$numpages = floor(mysql_numrows($result1)/20)+1;
		
		for ($pages = 0; $pages < $numpages; $pages++) {
	 		 $h = $h . '		<li><a href="api.php?action=list-log&p='.($pages+1).'">'.($pages+1).'</a></li>';
		}
 		
 		 $h = $h . ' 		<li><a href="api.php?action=list-log&p='.$NP1.'">&raquo;</a></li>';
 		 $h = $h . '	</ul>';
 		 $h = $h . '</div>';
        
		$h = $h . "</div>";

        echo $htop;
        echo $h;
        echo $hbot;
        
        
		
} else if ( $action == "list-stats-graph" ) {

	$db = mysql_connect($dbhost,$username,$password);
    mysql_select_db($database) or die("Unable to select database");
    mysql_query("SET NAMES utf8", $db);
    mysql_query( "SET CHARACTER SET utf8", $db );
    
    $query1="select distinct action as a from stats order by action";
    $result1=mysql_query($query1);
    $current_act = "";
    $actionz = $_POST['act'];
    
    $h = "";
    $h = $h . "<form>";
	$h = $h . "<input type='hidden' name='action' value='list-stats-graph'>";
	$h = $h . "<table class='table table-striped table-bordered'>";
    $h = $h . "<tr>";
    $h = $h . "<td>Action:</td><td><select name='act' id='act'>";
	for ($i=0; $i < mysql_numrows($result1); $i++){
		$act = mysql_result($result1,$i,"a");
    	$act = explode("&", $act);
    	$this_act = $act[0];
		if ($this_act != $current_act) {
			$h = $h . "<option value='".$act[0]."'>".$act[0]."</option>";
			$current_act = $this_act;
		}
	}
	$h = $h . "</select></td></tr>";
	$h = $h . '<tr><td colspan="2"><button type="submit" class="btn btn-large btn-success btn-block">Send</button></td></tr>';
	$h = $h . "<tr><td colspan='2'>";
	$current_hr ="";
	if ( $actionz != "") {
		
		$query="select HOUR(ts) as hr from stats where action like '%".$actionz."%' order by hr";
    	$result=mysql_query($query);
		$h = $h . "<div id='graphy' style='width:100%;height:300px;background-color:transparent;'>";
		for ($i=0; $i < mysql_numrows($result); $i++){
    		$hr = mysql_result($result,$i,"hr");
    		
    		$this_hr = $hr;
    		if ($this_hr != $current_hr) {
    			
    			$h = $h . "<div style='height:300px;width:10px;float:left;background-color:transparent;'></div>";
    			$h = $h . "<div style='height:300px;width:20px;float:left;background-color:transparent;'>";
    			
    			$query2="select * from stats where HOUR(ts) = '".$this_hr."'";
    			$result2=mysql_query($query2);
    			$xhr = mysql_numrows($result2);
    			$xthr = 300-$xhr;
    			
    			$h = $h . "		<div style='height:".$xthr."px;width:20px;background-color:transparent;float:left;'><p style='color:#999;margin:0;font-size:8px;margin-left:7px;margin-top:-5px;'>".$hr."</p></div>";
    			$h = $h . "		<div style='height:".$xhr."px;width:20px;background-color:#999;float:left;'><p style='color:#fff;margin:0;font-size:8px;margin-left:6px;margin-top:-5px;'>".$xhr."</p></div>";
    			
    			$h = $h . "</div>";
    			$current_hr = $this_hr;
    		
    		}
    			
    	}
		$h = $h . "</div>";
	
	} else{
	
		$h = $h . "";
	
	}
	$h = $h . "</td></tr>";
    $h = $h . "</table>";  
    $h = $h . "</form>";    
    
    echo $htop;
	echo '<div class="plain-hero-unit">';
	echo $h;
	echo '</div>';
	echo $hbot;

} else if ( $action == "list-stats-srch" ) {
	$db = mysql_connect($dbhost,$username,$password);
    mysql_select_db($database) or die("Unable to select database");
    mysql_query("SET NAMES utf8", $db);
    mysql_query( "SET CHARACTER SET utf8", $db );
    
    $query1="select distinct action as a from stats";
    $result1=mysql_query($query1);
    
		$act = $_POST["act"];
		$toy = $_POST["toy"];
		$tom = $_POST["tom"];
		$tod = $_POST["tod"];
		$fromy = $_POST["fromy"];
		$fromm = $_POST["fromm"];
		$fromd = $_POST["fromd"];
    
	$h = "";
	$h = $h . "<form>";
	$h = $h . "<input type='hidden' name='action' value='list-stats-srch'>";
	$h = $h . "<table class='table table-stripped'>";
	
	$h = $h . "<tr>";
	$h = $h . "<td>Action:</td><td colspan='3'><select name='act' id='act'>";
	$h = $h . "<option value='all'>All</option>";
	for ($i=0; $i < mysql_numrows($result1); $i++){
		if ( mysql_result($result1,$i,"a") != ""){
			$h = $h . "<option value='".mysql_result($result1,$i,"a")."'>".mysql_result($result1,$i,"a")."</option>";
		}
	}
	
	$h = $h . "</select></td></tr>";
	
	
	$h = $h . "<tr>";
	$h = $h . '<td>From:</td><td colspan="2"><div class="controls controls-row"><input class="span2" name="fromy" id="fromy" type="text" placeholder="Year" style="height:30px;">&nbsp;&nbsp;<input class="span1" name="fromm" id="fromm" type="text" placeholder="Month" style="height:30px;">&nbsp;&nbsp;&nbsp;<input class="span1" name="fromd" id="fromd" type="text" placeholder="Day" style="height:30px;"></div></td></tr><tr>';
	$h = $h . '<td>To:</td><td colspan="2"><div class="controls controls-row"><input class="span2" name="toy" id="toy" type="text" placeholder="Year" style="height:30px;">&nbsp;&nbsp;<input class="span1" name="tom" id="tom" type="text" placeholder="Month" style="height:30px;">&nbsp;&nbsp;&nbsp;<input class="span1" name="tod" id="tod" type="text" placeholder="Day" style="height:30px;"></div></td></tr><tr>';
	$h = $h . '<td colspan="2"><button type="submit" class="btn btn-success btn-large btn-primary">Send</button></td></tr>';
	
	$h = $h . "";
	$h = $h . "";
	$h = $h . "";
	$h = $h . "";
	$h = $h . "";
	$h = $h . "";
	$h = $h . "";
	$h = $h . "";
	$h = $h . "";
	$h = $h . "";
	$h = $h . "";
	$h = $h . "<tr><th>Action</th><th>Timestamp</th></tr>";
	$query="";
	$result="";
	
if ($act ==""){
	$query="select * from stats order by ts";
	$result=mysql_query($query);
		
}else{
	if ($act=="all"){
		if ($fromd=="" && $fromm=="" && $fromy=="" && $toy=="" && $tom=="" && $tod==""){
			$query="select * from stats where action like '%%' order by action limit 1500";
			echo $query;
			$result=mysql_query($query);	
		} else{
			$query="select * from stats where action like '%%' and ts between '".$fromy."-".$fromm."-".$fromd."  00:00:00' and '".$toy."-".$tom."-".$tod."  23:59:59' order by action limit 1500";
				echo $query;
				$result=mysql_query($query);	
		}
				
	}else{
		if ($fromd=="" && $fromm=="" && $fromy=="" && $toy=="" && $tom=="" && $tod==""){
			$query="select * from stats where action='".$act."' order by action limit 1500";
			echo $query;
			$result=mysql_query($query);	
				
		} else{
			$query="select * from stats where action='".$act."' and ts between '".$fromy."-".$fromm."-".$fromd."  00:00:00' and '".$toy."-".$tom."-".$tod."  23:59:59' order by action limit 1500";
			echo $query;
			$result=mysql_query($query);
		}
	}
}
	
	
	for ($i1=0; $i1 < mysql_numrows($result); $i1++){
	
		$h = $h . "<tr>";
		$h = $h . "<td>".mysql_result($result,$i1,"action")."</td><td>".mysql_result($result,$i1,"ts")."</td>";
		$h = $h . "</tr>";
	
	}
	
	$h = $h . "</table>";
	$h = $h . "</form>";
	
	echo $htop;
	echo '<div class="plain-hero-unit">';
	echo $h;
	echo '</div>';
	echo $hbot;

}else if ( $action == "setup" ) {
	
	$db = mysql_connect($dbhost,$username,$password);
    mysql_select_db($database) or die("Unable to select database");
    mysql_query("SET NAMES utf8", $db);
    mysql_query( "SET CHARACTER SET utf8", $db );
	$sel = $_POST['selected'];
	
	$action2 = $_POST['action2'];
	
	if ( $action2 == "update" ){
			
		$id = $_POST['id'];
		$data = $_POST['data'];
		$data = mysql_real_escape_string($data);

		$query8="UPDATE setup SET data='$data' WHERE id='$id'";
		$result8=mysql_query($query8);
		//echo $query8;
			
	}
	
	$h = "";
	$h = $h . '<div class="plain-hero-unit">';
	$h = $h . "<a href='api.php?action=setup&selected=test1'>Test1</a><br>";
	$h = $h . "<a href='api.php?action=setup&selected=test2'>Test2</a><br>";
	$h = $h . "<a href='api.php?action=setup&selected=test3'>Test3</a><br>";
	$h = $h . "<br><br>";
	$h = $h . "";
	$h = $h . "</div>";
	
	$js = "";
	$js = $js . "<script type='text/javascript'>";
	$js = $js . "";
	$js = $js . "</script>";
	
	
	if ( $sel != "" ){
		
		$h = $h ."<h1>". $sel ."</h1><br>";
		
		$query7="select * from setup where type='".$sel."'";
		$result7=mysql_query($query7);
		//echo $query7;
		
		$h = $h . "<table class='table'>";
		$h = $h . "<form>";
		$h = $h . "<input type='hidden' name='action' value='setup'>";
		$h = $h . "<input type='hidden' name='action2' value='update'>";
		$h = $h . "<input type='hidden' name='selected' value='".$sel."'>";
		for ($r7=0; $r7 < mysql_numrows($result7); $r7++) {
		$h = $h . "<input type='hidden' name='id' value='".mysql_result($result7,$r7,"id")."'>";
			$h = $h . "<tr>";
			$h = $h . "<td>".mysql_result($result7,$r7,"name")."</td><td><input type='text' name='data' value='".mysql_result($result7,$r7,"data")."'></td>";
			$h = $h . "</tr>";
		}
		$h = $h . "<tr><td colspan='2'><input type='submit' value='Save' class='btn btn-success'></td></tr>";
		$h = $h . "</form>";
		$h = $h . "</table>";
		
	}
	
	
	echo $htop;
	echo $h;
	echo $js;
	echo $hbot;

}else if ( $action == "show-log" ) {

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $id = $_POST["id"];
        $action2 = $_POST["action2"];
        $h = "";

        if ($action2 == "upload") {

                $target = $images_folder;

                $target = $target . basename( $_FILES['media']['name']) ;
                $ok=1;
                if(move_uploaded_file($_FILES['media']['tmp_name'], $target)) {
                        $db = mysql_connect($dbhost,$username,$password);
                        mysql_select_db($database) or die("Unable to select database");
                        mysql_query("SET NAMES utf8", $db);
                        mysql_query( "SET CHARACTER SET utf8", $db );

                        $img = basename( $_FILES['media']['name']);

                        $query="update ignore cats set  img='".$img."' where id=" . $id;
                        $result=mysql_query($query);
                }
        }


       else if ($action2 == "update") {
       			$id = $_POST["id"];
                $gname = $_POST["uid"];
                $gid = $_POST["gid"];
                $ts = $_POST["ts"];
                $un = $_POST["un"];
                $query="update ignore log_phone set  un='".$un."', uid='".$gname."', gid='".$gid."', ts=now() where id=" . $id;
                $result=mysql_query($query);
                $h = "UPDATED";
                $h = $h . "<a class='btn' href='api.php?action=show-log&id=" . $id . "'>OK <i class='icon-check'></i></a>";
        } else {

        $query="select * from log_phone where id=" . $id;
        $result=mysql_query($query);


		$query1="select * from groups";
        $result1=mysql_query($query1);
        $sel = "<select name='gid'>";
        $sel = $sel . "<option value='0'>Geen</option>";
        for ($r1=0; $r1 < mysql_numrows($result1); $r1++) {
            $selected = "";
            $IID = mysql_result($result1,$r1,"gid");
            $PGN = mysql_result($result1,$r1,"gname");

            if ($IID == mysql_result($result,$r,"gid")) $selected = "selected";
            $sel = $sel . "<option value='" .$IID."' ".$selected.">".$PGN."</option>";
        }
        $sel = $sel . "</select>";

        $h = "";
        $h = $h . "<h2>Showing device #". $id ."</h2>";

        if (mysql_numrows($result) > 0) {
                $h = $h . "<form action='api.php'>";
                $h = $h . "<input type='hidden' name='action' value='show-log'>";
                $h = $h . "<input type='hidden' name='action2' value='update'>";
                $h = $h . "<input type='hidden' name='id' value='".$id."'>";
                $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
                $h = $h . "<tr><td>".L('uid')."</td><td><input name='uid' type='text' class='test' value='" . mysql_result($result,$r,"uid") . "'></td></tr>";
                $h = $h . "<tr><td>".L('gid')."</td><td>".$sel."</td></tr>";
                $h = $h . "<tr><td>".L('un')."</td><td><input name='un' type='text' class='test' value='" . mysql_result($result,$r,"un") . "'></td></tr>";
                $h = $h . "<tr><td>&nbsp;</td><td><input class='btn btn-success' type='submit'></td></tr>";
                $h = $h . "</table>";
                $h = $h . "</form>";

			    $h = $h . "<form action='api.php'>";
                $h = $h . "<input type='hidden' name='action' value='show-log'>";
                $h = $h . "<input type='hidden' name='action2' value='delete'>";
                $h = $h . "<input type='hidden' name='gid' value='".$id."'>";
                $h = $h . "<input class='btn btn-danger btn-large' type='submit' value='delete'>";
                $h = $h . "</form>";

        	}
        }

    echo $htop;
    echo '<div class="plain-hero-unit">';
    echo $h;
    echo "</div>";
    echo $hbot;

}else if ( $action == "list-group" ) {
		
		$h = "";
        $h = $h . "<div class='plain-hero-unit'>";
//        $h = $h . "<div class='span3'>";
//        $h = $h . "Instructions";
//        $h = $h . "</div>";
        $h = $h . "<div class='span12'>";
		$h = $h . "";
		$db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

		$action2 = $_POST["action2"];
        if ($action2 == "add") {
                $query="insert ignore into groups (gid,gname) values (0,'change')";
                $result=mysql_query($query);
        }

        $query="select * from groups order by gid desc limit 50";
        $result=mysql_query($query);
        $h = $h . "<legend>".L('groupz')." <a class='btn btn-mini btn-success' style='margin-left:48px;' href='api.php?action=list-group&action2=add'> <i class='icon-plus icon-white'></i> ADD</a></legend>";

        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        $h = $h . "<th>group-id</th>";
        $h = $h . "<th>group-name</th>";
        $h = $h . "<th>".L('options')."</th>";
        $h = $h . "</tr>";

        for ($r=0; $r < mysql_numrows($result); $r++) {
                $h = $h . "<tr>";
                $h = $h . "<td>" . mysql_result($result,$r,"gid") . "</td>";  
                $h = $h . "<td>" . mysql_result($result,$r,"gname") . "</td>";
                $h = $h . "<td>" . B("edit","api.php?action=show-group&id=" . mysql_result($result,$r,"gid")) . "";
                $h = $h . " ";
                $h = $h . B("compose","javascript:\$push2press.push2(".mysql_result($result,$r,"gid").", \"\", \"\", \"\");'");
                $h = $h . " ";
                $h = $h . "" . B("delete","api.php?action=del-group&id=" . mysql_result($result,$r,"gid")) . "</td>";
                $h = $h . "</tr>";

        }
        $h = $h . "</table>";
		$h = $h . "</div>";
		$h = $h . "</div>";
		$h = $h . "</div>";
		
        echo $htop;
        echo $h;
        echo $hbot;
        
}else if ( $action == "del-group" ){

	$db = mysql_connect($dbhost,$username,$password);
    mysql_select_db($database) or die("Unable to select database");
	$id = $_GET["id"];
	$query="delete ignore from groups  where gid=" . $id;
    $result=mysql_query($query);
    echo "SUCCES!";
    exit;

}else if ( $action == "del-log" ){

	$db = mysql_connect($dbhost,$username,$password);
    mysql_select_db($database) or die("Unable to select database");
	$id = $_GET["id"];
	$query="delete ignore from log_phone  where id=" . $id;
    $result=mysql_query($query);
    echo "SUCCES!";
    exit;

}else if ( $action == "show-group" ) {

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $id = $_POST["id"];
        $action2 = $_POST["action2"];
        $h = "";

        if ($action2 == "upload") {

                $target = $images_folder;

                $target = $target . basename( $_FILES['media']['name']) ;
                $ok=1;
                if(move_uploaded_file($_FILES['media']['tmp_name'], $target)) {
                        $db = mysql_connect($dbhost,$username,$password);
                        mysql_select_db($database) or die("Unable to select database");
                        mysql_query("SET NAMES utf8", $db);
                        mysql_query( "SET CHARACTER SET utf8", $db );

                        $img = basename( $_FILES['media']['name']);

                        $query="update ignore cats set  img='".$img."' where id=" . $id;
                        $result=mysql_query($query);
                }
        }

       else if ($action2 == "update") {
       			$id = $_POST["gid"];
                $gname = $_POST["gname"];
                $query="update ignore groups set  gname='".$gname."' where gid=" . $id;
                $result=mysql_query($query);
                $h = "<div class='alert'>UPDATED</div>";
                $h = $h . "<a class='btn' href='api.php?action=list-groups'>OK <i class='icon-check'></i></a>";
        } else {

        $query="select * from groups where gid=" . $id;
        $result=mysql_query($query);

        $h = "";
        $h = $h . "<h2>Showing group # ". $id ."</h2>";

        if (mysql_numrows($result) > 0) {
                $h = $h . "<form action='api.php'>";
                $h = $h . "<input type='hidden' name='action' value='show-group'>";
                $h = $h . "<input type='hidden' name='action2' value='update'>";
                $h = $h . "<input type='hidden' name='gid' value='".$id."'>";
                $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
                $h = $h . "<tr><td>gname</td><td><input name='gname' type='text' value='" . mysql_result($result,$r,"gname") . "'></td></tr>";
                $h = $h . "<tr><td>&nbsp;</td><td><input class='btn btn-success' type='submit'></td></tr>";
                $h = $h . "</table>";
                $h = $h . "</form>";

                $h = $h . "<form action='api.php'>";
                $h = $h . "<input type='hidden' name='action' value='show-group'>";
                $h = $h . "<input type='hidden' name='action2' value='delete'>";
                $h = $h . "<input type='hidden' name='gid' value='".$id."'>";
                $h = $h . "<input class='btn btn-danger btn-large' type='submit' value='delete'>";
                $h = $h . "</form>";

        	}
        }

        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;

}else if ( $action == "send-uid" ) {

  

}else if ( $action == "gps-ping" ) {

        $data = mysql_escape_string($_POST["data"]);
        $uuid = $_POST["uuid"];
        $id = $_POST["id"];
        $event = $_POST["event"];
        $lat = $_POST["lat"];
        $lon = $_POST["lon"];
        $ts2 = $_POST["ts2"];

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );
        $query="insert ignore into events (id,ts,uuid,timer_id,lat,lon,ts2,event) values (0,now(),'" . $uuid . "','" . $id . "','" . $lat . "','" . $lon . "','" . $ts2 . "','" . $event . "')";
        $result=mysql_query($query);
        mysql_close($db);

        $retval = new obj();
        $retval->status = 0;
        $retval->status_txt = "log added";
        echo json_encode($retval);

} else if ( $action == "update" ) {
        $data = mysql_escape_string($_POST["data"]);
        $uuid = $_POST["uuid"];
        $id = $_POST["id"];
        $xaction = $_POST["xaction"];

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $action_comment = "";

        if ($id && $id != null && $id != '') {
                $query="update ignore timers set ts=now(),data='" . $data . "' where id=" . $id . "";
                $result=mysql_query($query);
                $action_comment = "updated";
        } else {
                $query="insert ignore into timers (id,ts,uuid,data,status) values (0,now(),'" . $uuid . "','" . $data . "','active')";
                $result=mysql_query($query);
                $id = mysql_insert_id($db);
                $action_comment = "added";
        }
        mysql_close($db);

        $retval = new obj();
        $retval->status = 0;
        $retval->status_txt = "timer uploaded " . $action_comment;
        $retval->data = new obj();
        $retval->data->id =$id;
        $retval->data->data =$data;
        echo json_encode($retval);

} else if ( $action == "info" ) {
        phpinfo();

} else if ( $action == "push" ) {
		
		$msgpusht = $_POST["msgpusht"];
		
		$osn = $_POST["osn"];
		
        $h = "";
        $h = $h . "<h2>Send Push Notification - ".$osn."</h2>";

        $action2 = $_POST["action2"];
        if ($action2 == "send") {
				$osn = $_POST["osn"];
                $Notification = $_POST["Notification"];
				$msgid = $_POST["msgid"];
				$msgdesc = $_POST["msgdesc"];

                $db = mysql_connect($dbhost,$username,$password);
                mysql_select_db($database) or die("Unable to select database");
                mysql_query("SET NAMES utf8", $db);
                mysql_query( "SET CHARACTER SET utf8", $db );
                $query="insert ignore into pushmessages values(0,now(),'".$Notification."','ok!')";
                $result=mysql_query($query);

if ($osn == "iphone" || $osn == "ipad"){

	date_default_timezone_set('Europe/Rome');

	// Report all PHP errors
	error_reporting(0);
	
	// Using Autoload all classes are loaded on-demand
	require_once './pushnotes/ApnsPHP/Autoload.php';

	// Instanciate a new ApnsPHP_Push object
	$push = new ApnsPHP_Push(ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,'./pushnotes/server_cerificates_bundle_sandbox.pem');

	// Set the Root Certificate Autority to verify the Apple remote peer
	$push->setRootCertificationAuthority('./pushnotes/entrust_root_certification_authority.pem');

	// Connect to the Apple Push Notification Service
	$push->connect();

	// Instantiate a new Message with a single recipient
	//$message = new ApnsPHP_Message('1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485');
	//$message = new ApnsPHP_Message('5fd4e4af4aec6b1e61b3d3c1d1165151ac3bfa29ef362bfdb482b3acf7ecea6a');
	$message = new ApnsPHP_Message($msgpusht);

	// Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
	// over a ApnsPHP_Message object retrieved with the getErrors() message.
	$message->setCustomIdentifier("Message-Badge-3");

	// Set badge icon to "3"
	$message->setBadge(1);

	// Set a simple welcome text
	$message->setText($Notification);

	// Play the default sound	
	$message->setSound();

	// Set a custom property
	$message->setCustomProperty('msgid', $msgid );

	// Set another custom property
	$message->setCustomProperty('msgdesc', $msgdesc );

	// Set the expiry value to 30 seconds
	$message->setExpiry(30);

	// Add the message to the message queue
	$push->add($message);

	// Send all messages in the message queue
	$push->send();

	// Disconnect from the Apple Push Notification Service
	$push->disconnect();

	// Examine the error message container
	$aErrorQueue = $push->getErrors();
} else if ($osn == "android"){

	// Replace with real client registration IDs 
	// $registrationIDs = array( "APA91bGVNNwLCKgU-pmZYuGTEgHKD0to_gmg-heAx1NrS3laZrlsSr16HFtBTzhOR1y6f0GE2JApDolLNh6Hj4OV9IQyLjdVgI1ah7Tdjb5WQ3yEZHWZ-CUEb1lHwqNzEqPGmM4raxCbMQQ8K9fY-DKSBbQ90dx6OQ", "456" );
	$registrationIDs = array($msgpusht);

	// Message to be sent
	$message = $Notification;

	// Set POST variables
	$url = 'https://android.googleapis.com/gcm/send';
	$apiKey = "AIzaSyCVuSmtmWHm30ChSdxmw0bQG-jD0hY-CW4";
	
	$fields = array(
	                'registration_ids'  => $registrationIDs,
	                'data'              => array( "message" => $message ),
	                );

	$headers = array( 
	                    'Authorization: key=' . $apiKey,
	                    'Content-Type: application/json'
	                );

	// Open connection
	$ch = curl_init();

	// Set the url, number of POST vars, POST data
	curl_setopt( $ch, CURLOPT_URL, $url );

	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	
	curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

	// Execute post
	$result = curl_exec($ch);

	// Close connection
	curl_close($ch);

	echo $result;

}
        	$h = $h . "<div class='alert'>Push notification sent</div>";

        }

        $h = $h . "<form action='api.php'>";
        $h = $h . "<input type='hidden' name='action' value='push'>";
        $h = $h . "<input type='hidden' name='action2' value='send'>";
        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        
        $h = $h . "<tr><td>Device</td><td><input type='text' name='msgpusht' value='$msgpusht'></td></tr>";
        $h = $h . "<tr><td>Name of os</td><td><input type='text' name='osn' value='$osn'></td></tr>";
        $h = $h . "<tr><td>Notification</td><td><textarea name='Notification'></textarea></td></tr>";
        $h = $h . "<tr><td>MesageID (if 0 then display desc.)</td><td><textarea name='msgid'></textarea></td></tr>";
        $h = $h . "<tr><td>Mesage Description</td><td><textarea name='msgdesc'></textarea></td></tr>";
        $h = $h . "<tr><td>&nbsp;</td><td><input type='submit'></td></tr>";
        $h = $h . "</table>";
        $h = $h . "</form>";

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $query="select * from pushmessages order by id desc limit 50";
        $result=mysql_query($query);
        $h = $h . "<h2>Recent messages</h2>";
        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        $h = $h . "<th>time</th>";
        $h = $h . "<th>Text</th>";
        $h = $h . "<th>Status</th>";
        $h = $h . "</tr>";

        for ($r=0; $r < mysql_numrows($result); $r++) {
                $h = $h . "<tr>";
                $h = $h . "<td>" . mysql_result($result,$r,"ts") . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"data") . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"status") . "</td>";
                $h = $h . "</tr>";

        }
        $h = $h . "</table>";

        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;

} else if ( $action == "list-cats" ) {

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $action2 = $_POST["action2"];
        if ($action2 == "add") {
                $query="insert ignore into cats (ID) values (0)";
                $result=mysql_query($query);
        }

        $query="select * from cats";
        $result=mysql_query($query);

        $h = "";
        $h = $h . "<legend>".L('catz')."</legend>";
        $h = $h . "<div class='btn-toolbar'>";
    	
    	$h = $h . "<div class='btn-group'  data-toggle='buttons-radio'>";
        $h = $h . "  <button onClick='javascript:' class='btn $f1active' style='visibility:hidden;'>".L("List_cats")."</button>";
    
		$h = $h . "</div>";		// end buttongroup
    
        $h = $h . "<div class='btn-group pull-right'>";
        $h = $h . "<a class='btn btn-success' href='api.php?action=list-cats&action2=add'> <i class='icon-plus icon-white'></i> ADD</a>";
        $h = $h . "</div>";		// end buttongroup
        
        $h = $h . "</div>";		// end toolbar
        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        $h = $h . "<th>".L('Title')."</th>";
        $h = $h . "<th>Caption</th>";
        $h = $h . "<th>image</th>";
        $h = $h . "<th>".L('options')."</th>";
        $h = $h . "</tr>";

        for ($r=0; $r < mysql_numrows($result); $r++) {
                $h = $h . "<tr>";
                $h = $h . "<td>" . mysql_result($result,$r,"Pagename") . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"Caption") . "</td>";
                $h = $h . "<td><img src='" . mysql_result($result,$r,"img") . "' width=32 height=32></td>";
                $h = $h . "<td><a class='btn btn-mini xbtn-success' href='api.php?action=show-cat&id=" . mysql_result($result,$r,"id") . "'><i class='icon-edit icon-black'></i> ".L("EDIT")."</a></td>";        
                $h = $h . "</tr>";

        }
        $h = $h . "</table>";

        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;

} else if ( $action == "show-cat" ) {

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $id = $_POST["id"];
        $action2 = $_POST["action2"];
        $h = "";


        $action2 = $_POST["action2"];
        if ($action2 == "add") {
                $query="insert ignore into cats (ID) values (0)";
                $result=mysql_query($query);
                $newid = mysql_insert_id();
                $id = $newid;
                $action2 = "";
        }

        if ($action2 == "upload") {

                $target = $images_folder;

                $target = $target . basename( $_FILES['media']['name']) ;
                $ok=1;
                if(move_uploaded_file($_FILES['media']['tmp_name'], $target)) {
                        $db = mysql_connect($dbhost,$username,$password);
                        mysql_select_db($database) or die("Unable to select database");
                        mysql_query("SET NAMES utf8", $db);
                        mysql_query( "SET CHARACTER SET utf8", $db );

                        $img = basename( $_FILES['media']['name']);

                        $query="update ignore cats set  img='".$img."' where id=" . $id;
                        $result=mysql_query($query);
                }
        }

        if ($action2 == "delete") {
                $query="delete ignore from cats  where id=" . $id;
                $result=mysql_query($query);
                $h = "DELETED";
                $h = $h . "<a class='btn' href='api.php?action=list-cats'>OK <i class='icon-check'></i></a>";
                $MSG = "<div class='alert alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Deleted</div>";
				$action = "homepage";


        } else if ($action2 == "update") {
                $Pagename = $_POST["Pagename"];
                $Caption = $_POST["Caption"];
                $img = $_POST["img"];
                $collapse = $_POST["collapse"];
                $query="update ignore cats set  img='".$img."', Caption='".$Caption."', Pagename='".$Pagename."', collapse='".$collapse."' where id=" . $id;
                $result=mysql_query($query);
                $h = "UPDATED";
//                $h = $h . "<a class='btn' href='api.php?action=show-cat&id=" . $id . "'>OK <i class='icon-check'></i></a>";
                $h = $h . "<a class='btn' href='api.php'>OK <i class='icon-check'></i></a>";
                $MSG = "<div class='alert alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Updated</div>";
				$action = "homepage";
//                continue;
                
        } else {

        $query="select * from cats where id=" . $id;
        $result=mysql_query($query);

        $h = "";
        $h = $h . "<h2>Showing Cat #".$id."</h2>";

        if (mysql_numrows($result) > 0) {
                $r=0;
                
                $img = mysql_result($result,$r,"img");
                $img_size = ($img && $img != "") ? sprintf("timthumb.php?h=32&w=32&src=%s",$img) : "";
				if (strpos($img_size,"client_images/images/icons/glyphicons")) $img_size = $img_size . "&zc=2&f=5,238,238,238,1";


                $h = $h . "<form action='api.php'>";
                $h = $h . "<input type='hidden' name='action' value='show-cat'>";
                $h = $h . "<input type='hidden' name='action2' value='update'>";
                $h = $h . "<input type='hidden' name='id' value='".$id."'>";
                $h = $h . "<input name='img' id='main_img_fld' type='hidden' value='" . mysql_result($result,$r,"img") . "'>";
                $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
/*
                $h = $h . "<tr><td>image</td><td id='test' class='previewimage'><input name='img' id='main_img_fld' type='hidden' value='" . mysql_result($result,$r,"img") . "'><img id='main_img' src='" . $img_size . "' height=32 width=32>&nbsp;&nbsp;&nbsp;<a class='btn' href='javascript:troller();'>".L("Browse")."</a></td></tr>";
				$h = $h ."<script type='text/javascript'>";
				$h = $h ."var finder = new CKFinder();";
				$h = $h ."finder.basePath = '".$BASEPATH."ckfinder/';";
				$h = $h . "function showFileInfo(a,b,c) {";
				$h = $h . "alert(a+' / '+b+' / '+c);";
				$h = $h . "$('#main_img').attr('src',a);";
				$h = $h . "$('#main_img_fld').val(a);";
				$h = $h . "";
				$h = $h . "}";
				$h = $h ."finder.selectActionFunction = showFileInfo;";
//				$h = $h ."function troller(){";
//				$h = $h ."finder.popup();";
//				$h = $h ."}";
				$h = $h ."</script>";
*/     
				$h = $h . "<tr><td>".L("Pagename")."</td><td><input name='Pagename' type='text' value='" . htmlspecialchars(mysql_result($result,$r,"Pagename"),ENT_QUOTES) . "'></td></tr>";
                $h = $h . "<tr><td>".L("Caption")."</td><td><input type='text' name='Caption' value='" . htmlspecialchars(mysql_result($result,$r,"Caption"),ENT_QUOTES) . "'></td></tr>";
                $h = $h . "<tr><td>".L("collapse")."</td><td><input type='text' name='collapse' value='" . htmlspecialchars(mysql_result($result,$r,"collapse"),ENT_QUOTES) . "'></td></tr>";
                $h = $h . "<tr><td>&nbsp;</td><td><input class='btn btn-success' type='submit'></td></tr>";
                $h = $h . "</table>";
                $h = $h . "</form>";

                $h = $h . "<form action='api.php'>";
                $h = $h . "<input type='hidden' name='action' value='show-cat'>";
                $h = $h . "<input type='hidden' name='action2' value='delete'>";
                $h = $h . "<input type='hidden' name='id' value='".$id."'>";
                $h = $h . "<input class='btn btn-danger btn-large' type='submit' value='delete'>";
                $h = $h . "</form>";

	        }


	        echo $htop;
	        echo '<div class="plain-hero-unit">';
	        echo $h;
	        echo "</div>";
	        echo $hbot;
        }

} 

else if ( $action == "list-dom" ) {

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $action2 = $_POST["action2"];
        if ($action2 == "add") {
                $query="insert ignore into domain (ID) values (0)";
                $result=mysql_query($query);
				echo $query;
        }

        $query="select * from domain";
        $result=mysql_query($query);
        
        
		/* filters */
		$filter2 = $_POST["filter2"];
		if ($filter2 && $filter2 != "") {
			$_SESSION['filter2'] = $filter2;
		} else {
			$filter2 = $_SESSION['filter2'];
		}
		if (!$filter2 || $filter2 == "") {
			$filter2 = "general";
		}
		
		require_once("etc/settings.php");
		$types = $settings->types;

        $h = "";
    	$h = $h . "<legend>".L('List_conf')." <a class='btn btn-mini btn-success' style='margin-left:40px;' href='api.php?action=list-dom&action2=add&filter2=other'> <i class='icon-plus icon-white'></i> ADD</a> </legend>";
        $h = $h . "<div class='btn-toolbar'>";
        $h = $h . "<div class='btn-group'  data-toggle='buttons-radio-1'>";
        
        foreach ($types as $type) {
        	$f2active = ($type == $filter2) ? "active" : "";
	        $h = $h . "  <button onClick='javascript:\$push2press.go(\"?action=list-dom&filter2=$type\")' class='btn $f2active'>$type ".L($type)."</button>";
	    }
        $h = $h . "</div>";		// end buttongroup
        
        $sdata = array();
        /* convert sql to Key/Val pairs */
        for ($r=0; $r < mysql_numrows($result); $r++) {
        	$sdata[mysql_result($result,$r,"Pagename")] = array(mysql_result($result,$r,"Caption"),mysql_result($result,$r,"id"));
        }

    
        
        $h = $h . "</div>";		// end toolbar
        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        $h = $h . "<th>".L('Title')."</th>";
        $h = $h . "<th>Value</th>";
        $h = $h . "<th>&nbsp;</th>";
        $h = $h . "</tr>";
        
        if ($filter2 == "other") {
			foreach (array_keys($sdata) as $skey) {
				if (is_in_settings($skey) == false) {
	                $h = $h . sprintf("<tr><td>%s</td><td>%s</td>",$skey,$sdata[$skey][0]);
	                $h = $h . "<td><a class='btn btn-mini xbtn-info' href='api.php?action=show-dom&id=" . $sdata[$skey][1] . "'>EDIT</a></td>";
	                $h = $h . "</tr>";
	             }
			}
        } else {
			foreach ($settings->table as $soption) {
				if ($soption[0] == $filter2) {
	                $h = $h . sprintf("<tr><td>%s<div>%s</div></td><td>%s</td>",$soption[1],$soption[2],$sdata[$soption[1]][0]);
	                $h = $h . "<td><a class='btn btn-mini xbtn-info' href='api.php?action=show-dom-key&key=" . $soption[1] . "'>EDIT</a></td>";
	                $h = $h . "</tr>";


				}
			}
        
        }
        $h = $h . "</table>";
        
        $h .= "<legend>Other functions</legend>";


        $h .= "<legend>Other functions</legend>";
        $h .='
        <div>
        <a class="btn" href="javascript:push2press.loading();">
		<img src="images/home/dark_tray-up@2x.png" width=16>
		&nbsp;
		UPDATE TO LATEST VERSION 
		</a>
		</div>';
		
		$h .= "<br>";
        
        $h .= '<div>
		<a class="btn" href="api.php?action=list-templates">
		<img src="images/home/dark_doc@2x.png" width=16>
		&nbsp;
		Manage Page Layout Templates 
		</a>
		</div>';

		$h .= "<br>";
        
/*
        for ($r=0; $r < mysql_numrows($result); $r++) {
                $h = $h . "<tr>";
                $h = $h . "<td>" . mysql_result($result,$r,"Pagename") . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"Caption") . "</td>";
                $h = $h . "<td><a class='btn btn-mini btn-info' href='api.php?action=show-dom&id=" . mysql_result($result,$r,"id") . "'>EDIT</a></td>";
                $h = $h . "</tr>";

        }
        
        $h = $h . "</table>";
*/        
		
        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;
}
 else if ( $action == "show-dom" ) {

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $id = $_POST["id"];
        $action2 = $_POST["action2"];
        $h = "";

        if ($action2 == "upload") {

                $target = $images_folder;

                $target = $target . basename( $_FILES['media']['name']) ;
                $ok=1;
                if(move_uploaded_file($_FILES['media']['tmp_name'], $target)) {
                        $db = mysql_connect($dbhost,$username,$password);
                        mysql_select_db($database) or die("Unable to select database");
                        mysql_query("SET NAMES utf8", $db);
                        mysql_query( "SET CHARACTER SET utf8", $db );

                        $img = basename( $_FILES['media']['name']);

                        $query="update ignore domain set  img='".$img."' where id=" . $id;
                        $result=mysql_query($query);
                }
        }

        if ($action2 == "delete") {
                $query="delete ignore from domain  where id='" . $id;
                $result=mysql_query($query);
                $h = "DELETED";
                $h = $h . "<a class='btn' href='api.php?action=list-dom'>OK <i class='icon-check'></i></a>";

        } else if ($action2 == "update") {
                $Pagename = $_POST["Pagename"];
                $Caption = $_POST["Caption"];
                $query="update ignore domain set  Caption='".$Caption."', Pagename='".$Pagename."' where id=" . $id;
//              echo $query;
                $result=mysql_query($query);
                $h = "UPDATED";
                $h = $h . "<a class='btn' href='api.php?action=list-dom>OK <i class='icon-check'></i></a>";
        } else {

        $query="select * from domain where id=" . $id;
        $result=mysql_query($query);

        $h = "";
        $h = $h . "<h2>Showing domain #".$id."</h2>";

        if (mysql_numrows($result) > 0) {
                $r=0;

                $h = $h . "<form action='api.php'>";
                $h = $h . "<input type='hidden' name='action' value='show-dom'>";
                $h = $h . "<input type='hidden' name='action2' value='update'>";
                $h = $h . "<input type='hidden' name='id' value='".$id."'>";
                $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
                $h = $h . "<tr><td>Pagename</td><td><input name='Pagename' type='text' value='" . htmlspecialchars(mysql_result($result,$r,"Pagename"),ENT_QUOTES) . "'></td></tr>";
                $h = $h . "<tr><td>Caption</td><td><input type='text' name='Caption' value='" . htmlspecialchars(mysql_result($result,$r,"Caption"),ENT_QUOTES) . "'></td></tr>";
                $h = $h . "<tr><td>&nbsp;</td><td><input class='btn btn-success' type='submit'></td></tr>";
                $h = $h . "</table>";
                $h = $h . "</form>";

                $h = $h . "<form action='api.php'>";
                $h = $h . "<input type='hidden' name='action' value='show-dom'>";
                $h = $h . "<input type='hidden' name='action2' value='delete'>";
                $h = $h . "<input type='hidden' name='id' value='".$id."'>";
                $h = $h . "<input class='btn btn-danger btn-large' type='submit' value='delete'>";
                $h = $h . "</form>";

       		}
        }

        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;

}

 else if ( $action == "show-dom-key" ) {

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $key = $_POST["key"];
        $action2 = $_POST["action2"];
        $h = "";

        if ($action2 == "update") {
                $Caption = $_POST["Caption"];
                $query="update ignore domain set  Caption='".$Caption."' where Pagename='".$key."'";
                $result=mysql_query($query);
                $h = "UPDATED";
                $h = $h . "<a class='btn' href='api.php?action=list-dom'>OK <i class='icon-check'></i></a>";
        } else {

	        $query="select * from domain where Pagename='" . $key . "'";
	        $result=mysql_query($query);

	        $h = "";
	        $h = $h . "<h2>Showing domain #".$key."</h2>";

	        if (mysql_numrows($result) > 0) {
                $r=0;

                $h = $h . "<form action='api.php'>";
                $h = $h . "<input type='hidden' name='action' value='show-dom-key'>";
                $h = $h . "<input type='hidden' name='action2' value='update'>";
                $h = $h . "<input type='hidden' name='id' value='".$id."'>";
                $h = $h . "<input type='hidden' name='key' value='".$key."'>";
                $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
                $h = $h . "<tr><td>Caption</td><td><input type='text' name='Caption' value='" . htmlspecialchars(mysql_result($result,$r,"Caption"),ENT_QUOTES) . "'></td></tr>";
                $h = $h . "<tr><td>&nbsp;</td><td><input class='btn btn-success' type='submit'></td></tr>";
                $h = $h . "</table>";
                $h = $h . "</form>";
       		}
        }

        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;

}

else if ( $action == "list-draft-mes" ) {

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $action2 = $_POST["action2"];
        if ($action2 == "add") {
                $query="insert ignore into message (ID,ts_added,ts_last_edited,status) values (0,now(),now(),'draft')";
                $result=mysql_query($query);
        }
        
        
		$filter1 = $_POST["filter1"];
		if ($filter1 && $filter1 != "") {
			$_SESSION['filter1'] = $filter1;
		} else {
			$filter1 = $_SESSION['filter1'];
		}
		if (!$filter1 || $filter1 == "") {
			$filter1 = "draft";
		}

		$f1active = "";
		$f2active = "";
		$f3active = "";
		$status_column = false;
		$where = "";
		
		switch ($filter1) {
		    case "draft":
		    	$where = $where . " and status='draft'";
		    	$f1active = "active";
		    	break;
		    case "sent":
		    	$where = $where . " and status='sent'";
		    	$f2active = "active";
		    	break;
		    case "all":
		    	$where = $where . "";
		    	$f3active = "active";
				$status_column = true;
		    	break;
		}        
       	$PAGE = $_POST['p'];
        if ( $PAGE == "" || $PAGE == "0"){
        	$PAGE="1";
        }
       	$PAGER = $PAGE-1;
		$LIM1= $PAGER*10;
		$LIM2= $LIM1+10;
		$query="select * from message order by ts_last_edited limit ".$LIM1.",".$LIM2;
		$result=mysql_query($query);
       	
        
        $query1="SELECT * from message where (1=1) " . $where;
        //echo $query;
        $result1=mysql_query($query1);
        

        $h = "";
        $h = $h . "<legend>".L('mesz')." <a class='btn btn-mini btn-success' style='margin-left:40px;' href='api.php?action=list-draft-mes&filter1=draft&action2=add'> <i class='icon-plus icon-white'></i> ".L("ADD")."</a> </legend>";
        $h = $h . "<div class='btn-toolbar'>";
        $h = $h . "<div class='btn-group'  data-toggle='buttons-radio'>";
        $h = $h . "  <button onClick='javascript:\$push2press.go(\"?action=list-draft-mes&filter1=draft\")' class='btn $f1active' style='xvisibility:hidden;'>".L("draft_messages")."</button>";
        $h = $h . "  <button onClick='javascript:\$push2press.go(\"?action=list-draft-mes&filter1=sent\")' class='btn $f2active' style='xvisibility:hidden;'>".L("sent_messages")."</button>";

        $h = $h . "  <button onClick='javascript:\$push2press.go(\"?action=list-draft-mes&filter1=all\")' class='btn $f3active' style='xvisibility:hidden;'>".L("all_messages")."</button>";

        $h = $h . "</div>";		// end buttongroup
        
        
        
        $h = $h . "</div>";		// end toolbar
        
        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        $h = $h . "<th>".L('Title')."</th>";
        if ($status_column == true) {
	        $h = $h . "<th>".L('Status')."</th>";
	    }
        $h = $h . "<th>".L('Date')."</th>";
        $h = $h . "<th>".L('options')."</th>";
        $h = $h . "</tr>";

        for ($r=0; $r < mysql_numrows($result); $r++) {
                $h = $h . "<tr>";
                $h = $h . "<td><a href='api.php?action=show-mes&id=" . mysql_result($result,$r,"id") . "'>" . mysql_result($result,$r,"Pagename") . "</a></td>";
		        if ($status_column == true) {
    	            $h = $h . "<td>" .mysql_result($result,$r,"status") . "</td>";
    	        }
                $h = $h . "<td>" . mysql_result($result,$r,"ts_last_edited") . "</td>";
                $h = $h . "<td>";
                $h = $h . B("preview","javascript:\$push2press.preview(\"api.php?action=get-mes-html&id=" . mysql_result($result,$r,"id")."\",\"".mysql_result($result,$r,"Pagename")."\",\"".mysql_result($result,$r,"Caption")."\",\"".mysql_result($result,$r,"img")."\");");
                $h = $h . " ";
                $h = $h . B("compose","javascript:\$push2press.push(".mysql_result($result,$r,"id").", \"\", \"\", \"\");'");
                $h = $h . " ";
                $h = $h . "<a class='btn btn-mini btn-xsuccess' href='api.php?action=show-mes&id=" . mysql_result($result,$r,"id") . "&p=".$PAGE."'><i class='icon-edit icon-green'></i> ".L("EDIT")."</a>";
                $h = $h . " ";
                //$h = $h . "<a class='btn btn-mini btn-primary' href='api.php?action=show-stats&id=" . mysql_result($result,$r,"id") . "'><i class='icon-random icon-white'></i> ".L("STATS")."</a>";
                $h = $h . "</td>";
                $h = $h . "</tr>";

        }
         $NP = $PAGE-1;
         $NP1 = $PAGE+1;	
         $h = $h . "</table>";
                $h = $h . '<div class="pagination pagination-small pagination-centered">';
  				$h = $h . '	<ul>';
    			$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p='.$NP.'">«</a></li>';
    			$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=1">1</a></li>';
    			
    			if ( mysql_numrows($result1) < 20 &&  mysql_numrows($result1) > 10 ){
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=2">2</a></li>';
    			} else if ( mysql_numrows($result1) < 30 &&  mysql_numrows($result1) > 10 ){
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=2">2</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=3">3</a></li>';
    			} else if ( mysql_numrows($result1) < 40 &&  mysql_numrows($result1) > 10 ){
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=2">2</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=3">3</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=4">4</a></li>';
    			} else if ( mysql_numrows($result1) < 50 &&  mysql_numrows($result1) > 10 ){
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=2">2</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=3">3</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=4">4</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=5">5</a></li>';
    			} else if ( mysql_numrows($result1) < 60 &&  mysql_numrows($result1) > 10 ){
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=2">2</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=3">3</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=4">4</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=5">5</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=6">6</a></li>';
    			} else if ( mysql_numrows($result1) < 70 &&  mysql_numrows($result1) > 10 ){
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=2">2</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=3">3</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=4">4</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=5">5</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=6">6</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=7">7</a></li>';
    			} else if ( mysql_numrows($result1) < 80 &&  mysql_numrows($result1) > 10 ){
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=2">2</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=3">3</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=4">4</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=5">5</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=6">6</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=7">7</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=8">8</a></li>';
    			} else if ( mysql_numrows($result1) < 90 &&  mysql_numrows($result1) > 10 ){
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=2">2</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=3">3</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=4">4</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=5">5</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=6">6</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=7">7</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=8">8</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=9">9</a></li>';
    			} else if ( mysql_numrows($result1) < 100 &&  mysql_numrows($result1) > 10 ){
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=2">2</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=3">3</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=4">4</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=5">5</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=6">6</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=7">7</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=8">8</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=9">9</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=10">10</a></li>';
    			} else if ( mysql_numrows($result1) < 110 &&  mysql_numrows($result1) > 10 ){
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=2">2</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=3">3</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=4">4</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=5">5</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=6">6</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=7">7</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=8">8</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=9">9</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=10">10</a></li>';
    				$h = $h . '		<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p=11">11</a></li>';
    			}
    			
   				$h = $h . ' 	<li><a href="api.php?action=list-draft-mes&filter1='.$filter1.'&p='.$NP1.'">»</a></li>';
  				$h = $h . '	</ul>';
				$h = $h . '</div>';
       
       
       
        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;
}
else if ( $action == "pushwindow") {

		$id = $_POST["id"];
		$mest = $_POST["mes"];
	
		$h = "";
		$db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $query="select * from groups order by gid desc limit 50";
        $result=mysql_query($query);
        
		$h = $h . "
		<script>
		function setformloading() {
			$"."('#submitformbutton').text('Loading');
			$"."('#form1').hide();
			$"."('#form1-spinner').show();
			return true;
		}
		</script>";
        
        $h = $h . "<legend>Select groups</legend>";
        $h = $h . "<form id='form1' name='form1' action='api.php' onsubmit='setformloading()'>";
        $h = $h . "<input type='hidden' name='action' value='push-prep'>";
        $h = $h . "<input type='hidden' name='id' value='".$id."'>";
        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        $h = $h . "<th>group-name</th>";
        $h = $h . "<th>select</th>";
        $h = $h . "</tr>";

        for ($r=0; $r < mysql_numrows($result); $r++) {
                $h = $h . "<tr>";
                $h = $h . "<td>" . mysql_result($result,$r,"gname") . "</td>";
                $h = $h . "<td><input type='checkbox' value='y' name='gid_".mysql_result($result,$r,"gid")."'></td>";
                $h = $h . "</tr>";

        }
        $h = $h . "</table>";
		
		$cap = "";
		if ($id && $id > 0) {
		
        	$query="select * from message where id = " . $id;
        	$result2=mysql_query($query);
        	if ( mysql_numrows($result2) > 0) {
        		$cap = mysql_result($result2,0,"Caption");
        	}
		}
		
    $h = $h . "<legend>Notification</legend>";
    $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
    $h = $h . "<tr>";
    $h = $h . "<td colspan='2'><textarea name='cap'>".$cap."</textarea></td>";
    $h = $h . "</tr>";
    
    $h = $h . "<tr>";
    $h = $h . "<td>Are you sure you want to sent this?</td>";
    $h = $h . "<td><button type='submit' id='submitformbutton' data-loading-text='Loading…' class='btn btn-mini btn-success'>send</button></td>";
    $h = $h . "</tr>";
    $h = $h . "</table>";	
    $h = $h . "</form>";

   	$h = $h . "<img src='images/ajax-spinner.gif' id='form1-spinner' style='display:none;'>";


	echo $htoppopup;
	echo $h;
	echo $hbotpopup;
	exit; 

	
} else if ( $action == "pushwindow-body") {

		$id = $_POST["id"];
		$mest = $_POST["mes"];
	
		$h = "";
		$db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $query="select * from groups order by gid desc limit 50";
        $result=mysql_query($query);
        
        $h = $h . "<legend>Select groups</legend>";
        $h = $h . "<form action='api.php'>";
        $h = $h . "<input type='hidden' name='action' value='push-prep'>";
        $h = $h . "<input type='hidden' name='id' value='".$id."'>";
        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        $h = $h . "<th>group-name</th>";
        $h = $h . "<th>select</th>";
        $h = $h . "</tr>";

        for ($r=0; $r < mysql_numrows($result); $r++) {
                $h = $h . "<tr>";
                $h = $h . "<td>" . mysql_result($result,$r,"gname") . "</td>";
                $h = $h . "<td><input type='checkbox' value='y' name='gid_".mysql_result($result,$r,"gid")."'></td>";
                $h = $h . "</tr>";

        }
        $h = $h . "</table>";
		
		$cap = "";
		if ($id && $id > 0) {
		
        	$query="select * from message where id = " . $id;
        	$result2=mysql_query($query);
        	if ( mysql_numrows($result2) > 0) {
        		$cap = mysql_result($result2,0,"Caption");
        	}
		}
		
    $h = $h . "<legend>Notification</legend>";
    $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
    $h = $h . "<tr>";
    $h = $h . "<td colspan='2'><textarea name='cap'>".$cap."</textarea></td>";
    $h = $h . "</tr>";
    
    $h = $h . "<tr>";
    $h = $h . "<td>Are you sure you want to sent this?</td>";
    $h = $h . "<td><input type='submit' class='btn btn-mini btn-success' value='send'></td>";
    $h = $h . "</tr>";
    $h = $h . "</form>";
    $h = $h . "</table>";	

	echo "<div id='pushwindow' style='padding:10px;'>";
	echo $h;
	echo "</div>";

} else if ( $action == "pushwindow1") {

		$id = $_POST["id"];
		$mest = $_POST["mes"];
	
		$h = "";
		$db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $query="select * from groups order by gid desc limit 50";
        $result=mysql_query($query);
        $h = $h . "<legend>Select groups</legend>";
        $h = $h . "<form action='api.php'>";
        $h = $h . "<input type='hidden' name='action' value='push-prep'>";
        //$h = $h . "<input type='hidden' name='action2' value='update'>";
        $h = $h . "<input type='hidden' name='id' value='".$id."'>";
        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        $h = $h . "<th>group-name</th>";
        $h = $h . "<th>select</th>";
        $h = $h . "</tr>";

        for ($r=0; $r < mysql_numrows($result); $r++) {
                $h = $h . "<tr>";
                $h = $h . "<td>" . mysql_result($result,$r,"gname") . "</td>";
                $h = $h . "<td><input type='checkbox' value='y' name='gid_".mysql_result($result,$r,"gid")."'></td>";
                $h = $h . "</tr>";

        }
        $h = $h . "</table>";
		
		$cap = "";
		if ($id && $id > 0) {
		
        	$query="select * from message where id = " . $id;
        	$result2=mysql_query($query);
        	if ( mysql_numrows($result2) > 0) {
        		$cap = mysql_result($result2,0,"Caption");
        	}
		}
		
    $h = $h . "<legend>Notification</legend>";
    $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
    $h = $h . "<tr>";
    $h = $h . "<td colspan='2'><textarea name='cap'>".$cap."</textarea></td>";
    $h = $h . "</tr>";
    
    $h = $h . "<tr>";
    $h = $h . "<td colspan='2'><textarea name='desc'>Typ hier nog een korte beschrijving.</textarea></td>";
    $h = $h . "</tr>";
    
    $h = $h . "<tr>";
    $h = $h . "<td>Are you sure you want to sent this?</td>";
    $h = $h . "<td><input type='submit' class='btn btn-mini btn-success' value='send'></td>";
    $h = $h . "</tr>";
    $h = $h . "</form>";
    $h = $h . "</table>";
		
	echo "<div id='pushwindow' style='padding:10px;'>";
	echo $h;
	echo "</div>";

}else if ( $action == "pushwindow2") {

		$id = $_POST["id"];
		$mest = $_POST["mes"];
	
		$h = "";
		$db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $query="select * from groups order by gid desc limit 50";
        $result=mysql_query($query);
        $h = $h . "<form id='form1' name='form1' action='api.php' onsubmit='setformloading()'>";
        $h = $h . "<input type='hidden' name='action' value='push-prep'>";
        $h = $h . "<input type='hidden' name='id' value='".$id."'>";
        $h = $h . "<input type='hidden' name='gid' value='".$id."'>";
        		
		$cap = "";
		$h = $h . "
		<script>
		function setformloading() {
			$"."('#submitformbutton').text('Loading');
			$"."('#form1').hide();
			$"."('#form1-spinner').show();
			return true;
		}
		</script>";
		
		$h = $h . "<legend>Notification</legend>";
	    $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
	    $h = $h . "<tr>";
	    $h = $h . "<td colspan='2'><textarea name='cap'>".$cap."</textarea></td>";
	    $h = $h . "</tr>";
    
	    $h = $h . "<tr>";
	    $h = $h . "<td colspan='2'><textarea name='desc'>Typ hier nog een korte beschrijving.</textarea></td>";
	    $h = $h . "</tr>";
    
	    $h = $h . "<tr>";
	    $h = $h . "<td>Are you sure you want to sent this?</td>";
	    $h = $h . "<td><input type='submit' id='submitformbutton' class='btn btn-mini btn-success' value='send'></td>";
	    $h = $h . "</tr>";
	    $h = $h . "</table>";
	    $h = $h . "</form>";
    	$h = $h . "<img src='images/ajax-spinner.gif' id='form1-spinner' style='display:none;'>";
		
	echo $htoppopup;
	echo "<div id='pushwindow' style='padding:10px;'>";
	echo $h;
	echo "</div>";
	echo $hbotpopup;
	exit; 

}else if ( $action == "pushwindow3") {
		$id = $_POST["id"];
		$mest = $_POST["mes"];
	
		$h = "";
		$db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $query="select * from log_phone where id='".$id."' order by gid desc limit 50";
        $result=mysql_query($query);
		$UID = "";
		$OSN = "";
		for ($r=0; $r < mysql_numrows($result); $r++) {
			$UID = mysql_result($result,0,"uid");
			$OSN = mysql_result($result,0,"osn");
		}
		$six_digit_random_number = mt_rand(100000, 999999);
		$h = $h . "
		<script>
		function setformloading() {
			$"."('#submitformbutton').text('Loading');
			$"."('#form1').hide();
			$"."('#form1-spinner').show();
			return true;
		}
		</script>";
        $h = $h . "<form id='form1' name='form1' action='api.php' onsubmit='setformloading()'>";
        $h = $h . "<input type='hidden' name='action' value='push-prep'>";
        $h = $h . "<input type='hidden' name='id' value='".$six_digit_random_number."'>";
        $h = $h . "<input type='hidden' name='device' value='".$UID."'>";
        $h = $h . "<input type='hidden' name='osn' value='".$OSN."'>";
        		
		$cap = "";
		
//		$h = $h . "<legend>Notification</legend>";
    	$h = $h . "<table class='table table-striped table-bordered table-condensed'>";
    	$h = $h . "<tr>";
    	$h = $h . "<td colspan='2'><textarea name='cap'>".$cap."</textarea></td>";
    	$h = $h . "</tr>";
    
    	$h = $h . "<tr>";
    	$h = $h . "<td colspan='2'><textarea name='desc'>Typ hier nog een korte beschrijving.</textarea></td>";
    	$h = $h . "</tr>";
    	
    	$h = $h . "<tr>";
    	$h = $h . "<td>Are you sure you want to sent this?</td>";
	    $h = $h . "<td><button id='submitformbutton' type='submit' data-loading-text='Loading…' class='btn btn-mini btn-success'>send</button></td>";
    	$h = $h . "</tr>";


    	$h = $h . "</table>";
    	$h = $h . "</form>";
    	$h = $h . "<img src='images/ajax-spinner.gif' id='form1-spinner' style='display:none;'>";
		

	echo $htoppopup;
	echo "<div id='pushwindow' style='padding:10px;'>";
	echo $h;
	echo "</div>";
	echo $hbotpopup;
	exit; 

}else if ( $action == "pushwindow3-body") {

		$id = $_POST["id"];
		$mest = $_POST["mes"];
	
		$h = "";
		$db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $query="select * from log_phone where id='".$id."' order by gid desc limit 50";
        $result=mysql_query($query);
		$UID = "";
		$OSN = "";
		for ($r=0; $r < mysql_numrows($result); $r++) {
			$UID = mysql_result($result,0,"uid");
			$OSN = mysql_result($result,0,"osn");
		}
		$six_digit_random_number = mt_rand(100000, 999999);
        $h = $h . "<form action='api.php'>";
        $h = $h . "<input type='hidden' name='action' value='push-prep'>";
        $h = $h . "<input type='hidden' name='id' value='".$six_digit_random_number."'>";
        $h = $h . "<input type='hidden' name='device' value='".$UID."'>";
        $h = $h . "<input type='hidden' name='osn' value='".$OSN."'>";
        		
		$cap = "";
		
		$h = $h . "<legend>Notification</legend>";
    	$h = $h . "<table class='table table-striped table-bordered table-condensed'>";
    	$h = $h . "<tr>";
    	$h = $h . "<td colspan='2'><textarea name='cap'>".$cap."</textarea></td>";
    	$h = $h . "</tr>";
    
    	$h = $h . "<tr>";
    	$h = $h . "<td colspan='2'><textarea name='desc'>Typ hier nog een korte beschrijving.</textarea></td>";
    	$h = $h . "</tr>";
    	
    	$h = $h . "<tr>";
    	$h = $h . "<td>Are you sure you want to sent this?</td>";
    	$h = $h . "<td><input type='submit' class='btn btn-mini btn-success' value='send'></td>";
    	$h = $h . "</tr>";
    	$h = $h . "</form>";
    	$h = $h . "</table>";
		

	echo "<div id='pushwindow' style='padding:10px;'>";
	echo $h;
	echo "</div>";	

} else if ( $action == "push-prep") {

		$id = $_POST["id"];
		$gid = $_POST["gid"];
		$gid_1 = $_POST['gid_1'];
		$gid_2 = $_POST['gid_2'];
		
		if ($gid_1 == "y" || $gid_1 == "Y") {
			$gid = "1";
		} else if ($gid_2 == "y" || $gid_2 == "Y"){
			$gid = "2";
		}
		$device = $_POST["device"];
		$osn = $_POST['osn'];
		$cap = $_POST["cap"];
		$desc = $_POST["desc"];
		$mid = $id;
		
		$h = "";
		$db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $query="insert into sending (id,date,eid,notification,msgDesc,status) values (0,now(),'$id','$cap','$desc','Setting-up')";
        $result=mysql_query($query);
        $_sendingid = mysql_insert_id();

		$updatemessagequery=sprintf("update message set status='sent' where id='%s'",$id);
		mysql_query($updatemessagequery);


      	//echo $query;
      	$READY = "N";
      	$REALDEVICE = "";
      	if( $device != "" ) {
      		
      		$REALDEVICE = $device;
      		//echo $REALDEVICE;
      		$query1="insert into recipient (rid, mid, devID, osn, date, status) values ('$_sendingid',0,'$device','$osn', now(),'ready_to_send')";
      		$result1=mysql_query($query1);
      		
      		$READY = "Y";
      	
      	} else if( $device == "" && $gid != "") {
      		
      		$query2="select * from log_phone where uid != '' and uid != 'null' and ( groups = '$gid' or groups like '$gid,%' or groups like '%,$gid' or groups like '%,$gid,%')";
      		$result2=mysql_query($query2);
      		
      		for ($r=0; $r < mysql_numrows($result2); $r++) {
      		
      			$device1 = mysql_result($result2,$r,"uid");
      			$REALDEVICE = $device1;
      			echo $REALDEVICE;
      			$osn1 = mysql_result($result2,$r,"osn");
      			
      			$query1="insert into recipient (rid, mid, devID, osn, date, status) values ('$_sendingid',0,'$device1','$osn1', now(),'ready_to_send')";
      			$result1=mysql_query($query1);
      		
      		}
      		
      		//$h = $h . "Being sent!!!!!";
      		$READY = "Y";
      	}else{
      		
      		$h = $h . "ERROR NOT ABLE TO SENT!!!!!";
      		$READY = "N";
      		
      	}
      	$ERRORZ = "";

		error_log("push-prep - ready [" . $READY . "]");
		
      	//echo $READY;
      	if( $READY == "Y" ){
      		
      		//$query3="select * from recipient left join sending on id=rid where recipient.status='ready_to_send'";
      		//$query3="select * from recipient left join sending on rid=eid where recipient.status='ready_to_send'";
      		$query3="select * from recipient left join sending on rid=id where recipient.status='ready_to_send'";
     		$result3=mysql_query($query3);
      		//echo $query3;
      		
      		
      		$msgpusht = "";
      		$RIDZ = "";

			error_log("push-prep - rows result3 [" . mysql_numrows($result3) . "]");
      		
      		
      		for ($r=0; $r < mysql_numrows($result3); $r++) {

				error_log("push-prep - rows result3  row[" . $r . "]");
      		
      			$msgpusht = mysql_result($result3,$r,"devID");
      			$RIDZ = mysql_result($result3,$r,"rid");
      			$osn1 = mysql_result($result3,$r,"osn");
      			$notification = mysql_result($result3,$r,"notification");
				$msgid = mysql_result($result3,$r,"eid");
				$msgdesc = mysql_result($result3,$r,"msgDesc");
				/*$msgpusht = "14d67d38eb1b1170248fc500d35c465576792e7b4c119c9c8db81fe2bd5e2abe";
      			$RIDZ = "17";
      			$osn1 = "iphone";
      			$notification = "test";
				$msgid = "17";
				$msgdesc = "";*/
				
				// echo "OSN1 $osn1";

				error_log("push-prep - rows result3  row[" . $r . "] osn1 [ $osn1 ]");
				
				
				//$h = $h . "a".$notification."<br> query:".$query3;
				//start of pusher
				if (($osn1 == "iphone" || $osn1 == "ipad")){

					date_default_timezone_set('Europe/Rome');
	
					// Report all PHP errors
					//error_reporting(0);
	
					// Using Autoload all classes are loaded on-demand
					require_once './pushnotes/ApnsPHP/Autoload.php';
	
					$sql4 = "select * from log_phone where uid='$msgpusht' and osn='$osn1'";
		      		$result4 = mysql_query($sql4);
	      		
		      		$APNS_ENVIRONMENT = ApnsPHP_Abstract::ENVIRONMENT_SANDBOX;
		      		$APNS_PEMFILE = "aps";
		      		$APNS_PEMFILE_TYPE = "development";
		      		$apn_aid = "";
	      		
	      		
		      		if (mysql_numrows($result4) > 0) {
		      			
		      			if (mysql_result($result4,0,"dtype") == "production") {
			      			$APNS_ENVIRONMENT = ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION;
				      		$APNS_PEMFILE_TYPE = "production";
		      			}
		      			$apn_aid = mysql_result($result4,0,"aid");
		      			if ($apn_aid == "com.glimworm.app.push2press") {
			      			$APNS_PEMFILE = "com.glimworm.app.push2press_aps";
		      			}
		      		
		      		}
		      		$PEMFILENAME = sprintf("./pushnotes/%s_%s.pem", $APNS_PEMFILE, $APNS_PEMFILE_TYPE); 
		      		
					error_log("push-prep - rows result3  row[" . $r . "] apn_aid [ $apn_aid ]");

					error_log("push-prep - rows result3  row[" . $r . "] PEMFILENAME [ $PEMFILENAME ]");
					
					if (file_exists($PEMFILENAME) == false) {
						error_log("push-prep - rows result3  row[" . $r . "] PEMFILENAME [ $PEMFILENAME ] does not exist!!");
						continue;
					}
		      		
		      		
					// echo "PEMFILENAME $PEMFILENAME";

			

					// Instanciate a new ApnsPHP_Push object
//					$push = new ApnsPHP_Push($APNS_ENVIRONMENT,'./pushnotes/server_cerificates_bundle_sandbox.pem');
					$push = new ApnsPHP_Push($APNS_ENVIRONMENT,$PEMFILENAME);


					// Set the Root Certificate Autority to verify the Apple remote peer
					$push->setRootCertificationAuthority('./pushnotes/entrust_root_certification_authority.pem');

	
					if ($msgpusht && $msgpusht != "" && $msgpusht != "null") {
					
						// Connect to the Apple Push Notification Service
						$push->connect();

						// Instantiate a new Message with a single recipient
						$message = new ApnsPHP_Message($msgpusht);

						// Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
						// over a ApnsPHP_Message object retrieved with the getErrors() message.
						$message->setCustomIdentifier("Message-Badge-3");
					
						// Set badge icon to "3"
						$message->setBadge(1);

						// Set a simple welcome text
						$message->setText($notification);
						//$message->setText("test");
					
						// Play the default sound	
						$message->setSound();

						// Set a custom property
						$message->setCustomProperty('msgid', $msgid );

						// Set another custom property
						$message->setCustomProperty('msgdesc', $msgdesc );
					
						// Set another custom property
						$message->setCustomProperty('sitename', getConfiguration("sitename","") );

						// Set another custom property
						$message->setCustomProperty('url', getConfiguration("url","") );

						// Set another custom property
						$message->setCustomProperty('appid', getConfiguration("appid","") );

						// Set the expiry value to 30 seconds
						$message->setExpiry(30);

						// Add the message to the message queue
						$push->add($message);

						// Send all messages in the message queue
						$push->send();
						
						// Disconnect from the Apple Push Notification Service
						$push->disconnect();
					}
				}
                
            $h = $h . sprintf("<!--div class='alert'>Push notification sent [%s] [%s] [%s] [%s] [%s]</div-->",$APNS_ENVIRONMENT,$APNS_PEMFILE,$APNS_PEMFILE_TYPE,$apn_aid,$sql4);
            
			$query6="update ignore recipient set  status='send' where devID='" .$msgpusht. "' and rid=".$RIDZ;
        	$result6=mysql_query($query6);
        	//echo $query6;
      	}

      	
      	$h = $h . "<div><img src='images/mobilesms.png'> Push Messages were sent successfully</div>";
      	
      	}else{
      		$h = $h . "ERROR!!!!!";
      	}
      	
		echo $htoppopup;
		echo '<div class="plain-hero-unit">';
		echo $h;
		echo '</div>';
		echo $hbotpopup;
		exit;

}else if ( $action == "show-mes" ) {

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $id = $_POST["id"];
        $action2 = $_POST["action2"];
        $h = "";
        $PAGE = $_POST['p'];
        
        if ($action2 == "delete") {
                $query="delete ignore from message  where id=" . $id;
                $result=mysql_query($query);
                $h = "DELETED";
                $h = $h . "<a class='btn' href='api.php?action=list-draft-mes'>OK <i class='icon-check'></i></a>";

        } else if ($action2 == "update") {
                $Pagename = $_POST["Pagename"];
                $Caption = $_POST["Caption"];
                $bodytext = $_POST["editor1"];
                $status = $_POST["status"];
                $ts_sent = $_POST["ts_sent"];
                $img = $_POST["img"];
				
				if ($img != ""){
                	$query="update ignore message set  img='".esc($img)."', Caption='".esc($Caption)."', Pagename='".esc($Pagename)."', bodytext='".esc($bodytext)."' , status='".esc($status)."',ts_sent='".$ts_sent."' where id=" . $id;
                	$result=mysql_query($query);
                	$h = "UPDATED";
                	$h = $h . "<a class='btn' href='api.php?action=show-mes&id=" . $id . "&p=".$PAGE."'>OK <i class='icon-check'></i></a>";
                }else{
                	$h = "Please use an image";
                	$h = $h . "<a class='btn' href='api.php?action=show-mes&id=" . $id . "&p=".$PAGE."'>OK <i class='icon-check'></i></a>";
                }
                
        }else {

        	$query="select * from message where id=" . $id;
        	$result=mysql_query($query);
	
    	    $h = "";

    	    if (mysql_numrows($result) > 0) {
                $r=0;

		        $h = $h . "<div class='btn-toolbar'>";
		        $h = $h . "<div class='pull-right'>";
		        $h = $h . "<div class='btn-group'>";
		        $h = $h . "<a class='btn btn-mini btn-success' href='api.php?action=list-draft-mes&p=".$PAGE."'>BACK</a>";
		        $h = $h . "</div>";
		        $h = $h . "<div class='btn-group'>";
                $h = $h . B("S&S","javascript:\$push2press.push(".$id.", \"\", \"\", \"\");'");
		        $h = $h . "</div>";		// end buttongroup
		        $h = $h . "<div class='btn-group'>";
                $h = $h . B("preview","javascript:\$push2press.preview(\"api.php?action=get-mes-html&id=" . $id."\");");
		        
		        $h = $h . "</div>";		// end buttongroup

		        $h = $h . "<div class='btn-group'>";
                $h = $h . B("delete","api.php?action=show-mess&action2=delete&id=".$id);
		        
		        $h = $h . "</div>";		// end buttongroup
		        $h = $h . "</div>";		// end toolbar
		        $h = $h . "</div>";		// end toolbar
		        
                $h = $h . "<form action='api.php' class='form-horizontal' method='POST'>";
                $h = $h . "<input type='hidden' name='action' value='show-mes'>";
                $h = $h . "<input type='hidden' name='action2' value='update'>";
                $h = $h . "<input type='hidden' name='id' value='".$id."'>";
                $h = $h . "<input type='hidden' name='p' value='".$PAGE."'>";
                
                $h = $h . "<div class='control-group'><label class='control-label' for=''>image</label>";
                $h = $h . "	<div class='controls'>";
                $h = $h . "	<input name='img' id='main_img_fld' type='hidden' value='" . mysql_result($result,$r,"img") . "'><img id='main_img' src='" . mysql_result($result,$r,"img") . "' height=80 width=80><a class='btn' href='javascript:troller();'>".L("Browse")."</a>";

                $h = $h . "</div>";
                $h = $h . "</div>";
                
                $h = $h . "<div class='control-group'><label class='control-label' for='Pagename'>".L("Pagename")."</label>";
                $h = $h . "	<div class='controls'>";
				$h = $h . "<input name='Pagename' type='text' value='" . htmlspecialchars(mysql_result($result,$r,"Pagename"),ENT_QUOTES) . "'>";
                $h = $h . "</div>";
                $h = $h . "</div>";

				$h = $h . "<div class='control-group'><label class='control-label' for='Caption'>".L("Caption")."</label>";
                $h = $h . "	<div class='controls'>";
				$h = $h . "<input name='Caption' type='text' value='" . htmlspecialchars(mysql_result($result,$r,"Caption"),ENT_QUOTES) . "'>";
                $h = $h . "</div>";
                $h = $h . "</div>";
                
                
				$h = $h . "<div class='control-group'><label class='control-label' for='Caption'>".L("Status")."</label>";
                $h = $h . "	<div class='controls'>";
				$h = $h . "<input name='status' type='text' value='" . mysql_result($result,$r,"status") . "'>";
                $h = $h . "</div>";
                $h = $h . "</div>";
				$h = $h . "<div class='control-group'><label class='control-label' for='Caption'>ts_sent</label>";
                $h = $h . "	<div class='controls'>";
				$h = $h . "<input id='_ts_sent' data-date-format='yyyy-mm-dd' name='ts_sent' type='text' value='" . mysql_result($result,$r,"ts_sent") . "'>";
                $h = $h . "</div>";
                $h = $h . "</div>";
                

				$h = $h . "<div class='control-group'><label class='control-label' for=''>".L("bodytext")."</label>";
                $h = $h . "	<div class='controls'>";
                $h = $h . "	<div id='topSpace-wrapper'>";
                $h = $h . "	<div id='topSpace'></div>";
                $h = $h . "	<div style='width:400px;' id='bottomSpace'>";
				$h = $h . "<textarea class='xckeditor' id='test123' style='width:300px;' id='editor1' name='editor1' rows='10' style='visibility: hidden; display: none;'>". htmlspecialchars(mysql_result($result,$r,'bodytext'),ENT_QUOTES) ."</textarea class='ckeditor'>";
                $h = $h .'</div>';
                $h = $h .'</div>';
                $h = $h .'<script type="text/javascript">';
				$h = $h . '$(function(){ $("#_ts_sent").datepicker(); });';
				$h = $h ." CKEDITOR.replace( 'test123',";
				$h = $h ."{";

				$h = $h ."	skin : 'BootstrapCK-Skin',";
				$h = $h ."	sharedSpaces : {top : 'topSpace',bottom : 'bottomSpace'},";
				$h = $h ."	toolbar : 'mytoolbar',";
				$h = $h ."	width : 300,";
				$h = $h ."	height : 360,";
				$h = $h ."	removePlugins : 'maximize,resize',";
				$h = $h ."	toolbar_mytoolbar : push2press.getEditorToolbar(),";

//config.skin = 'moono';
				$h = $h ."	filebrowserBrowseUrl : 'kcfinder/browse.php?type=files',";
				$h = $h ."	filebrowserImageBrowseUrl : 'kcfinder/browse.php?type=images',";
				$h = $h ."	filebrowserFlashBrowseUrl : 'kcfinder/browse.php?type=flash',";
				$h = $h ."	filebrowserUploadUrl : 'kcfinder/upload.php?type=files',";
				$h = $h ."	filebrowserImageUploadUrl : 'kcfinder/upload.php?type=images',";
				$h = $h ."	filebrowserFlashUploadUrl : 'kcfinder/upload.php?type=flash'";
				$h = $h ."});";
				$h = $h .'</script>';
                $h = $h . "</div>";
                $h = $h . "</div>";
                 
                $h = $h . "<div class='control-group'>";
                $h = $h . "	<div class='controls'>";
                $h = $h . "<button type='submit' class='btn btn-success' id='btn123'>UPDATE</button>";
               /* $url = $_SERVER['REQUEST_URI'];
                $h = $h . "<script type='text/javascript'>";
                $h = $h . "		$( '#btn123' ).click(function() {";
                $h = $h . "			var valx = $('#main_img_fld').val();";
                $h = $h . "			if(valx == ''){";
                $h = $h . "				alert('".$url."');";
                $h = $h . "				';";
                $h = $h . "			}else{";
                $h = $h . "				Alert('ok!');";
                $h = $h . "			}";
                $h = $h . "		});";
                $h = $h . "</script>";
                */
                $h = $h . "</div>";
                $h = $h . "</div>";
                
                $h = $h . "</form>";

				$h = $h .'<script type="text/javascript">';
				$h = $h ."\$push2press.ckedit('test123',500,500);";
				$h = $h .'</script>';

        	}
        }

        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;

} else if ( $action == "list-pages" ) {

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );


        $action2 = $_POST["action2"];
        if ($action2 == "add") {
                $query="insert ignore into pages (ID) values (0)";
                $result=mysql_query($query);
                $newid = mysql_insert_id();


				$url301 = "api.php?action=show-page&id=$newid";
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: $url301"); 
				exit;
                
                
                
                /*
        		echo "<br><br><br><br><pre>result::\n";
				var_dump($result);

                $result=mysql_query("show tables");
		        for ($r=0; $r < mysql_numrows($result); $r++) {
		        	echo mysql_result($result,$r,0) . "\n";
				}
                $result=mysql_query("describe pages");
		        for ($r=0; $r < mysql_numrows($result); $r++) {
		        	echo mysql_result($result,$r,0) . " / " .mysql_result($result,$r,1). "\n";
				}
				
				echo "</pre><br><br>";
				*/
        }

        $query="select *,c.Pagename as CatName from pages p left join cats c on (p.CatID = c.id) order by c.Volgorde,p.Volgorde";
        $result=mysql_query($query);



        $h = "";
        $h = $h . "<legend>".L('pagz')." <a class='btn btn-mini btn-success' style='margin-left:40px;' href='api.php?action=list-pages&action2=add'> <i class='icon-plus icon-white'></i> ADD</a></legend>";
        $h = $h . "<div class='btn-toolbar'>";
    	
/*
    	$h = $h . "<div class='btn-group' data-toggle='buttons-radio'>";
        $h = $h . "  <button onClick='javascript:' class='btn $f1active' style='visibility:hidden;'>".L("List_pages")."</button>";
        $h = $h . "</div>";		// end buttongroup
*/
    
//        $h = $h . "<div class='btn-group pull-right'>";
//        $h = $h . "<a class='btn btn-success' href='api.php?action=list-pages&action2=add'> <i class='icon-plus icon-white'></i> ADD</a>";
//        $h = $h . "</div>";		// end buttongroup
        
        $h = $h . "</div>";		// end toolbar
        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        $h = $h . "<th>".L('Title')."</th>";
        $h = $h . "<th>Caption</th>";
        $h = $h . "<th>image</th>";
        $h = $h . "<th>Volgorde</th>";
        $h = $h . "<th>Cat</th>";
        $h = $h . "<th>".L('options')."</th>";
        $h = $h . "</tr>";
		
        for ($r=0; $r < mysql_numrows($result); $r++) {
                $h = $h . "<tr>";
                $h = $h . "<td>" . mysql_result($result,$r,"Pagename") . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"Caption") . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"img") . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"Volgorde") . "</td>";
                $h = $h . "<td>" . mysql_result($result,$r,"CatName") . "</td>";
                $h = $h . "<td>" .B("edit","api.php?action=show-page&id=" . mysql_result($result,$r,"id") . ""). "";
                $h = $h . " ";
                $h = $h . "" .B("preview","javascript:\$push2press.previewpage(\"api.php?action=get-page&id=" . mysql_result($result,$r,"id")."\");"). "</td>";

                $h = $h . "</tr>";

        }
        $h = $h . "</table>";
        $h = $h . "<a class='btn xbtn-info' href='javascript:\$push2press.preview2(\"api.php?action=do-volgorde\");'> <i class='icon-resize-vertical icon-black'></i> Change Volgorde</a><br><br>";

        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;
		
} else if ( $action == "show-template" ) {
		$template = $_POST["id"];

        $action2 = $_POST["action2"];
        if ($action2 == "save") {
			$content = $_POST["content"];
			file_put_contents($template,$content);
        }

		$content = file_get_contents($template);
        $h = "";
        $h = $h . "<form action='api.php' method='post'>";
        $h = $h . "<input type='hidden' name='action' value='show-template'>";
        $h = $h . "<input type='hidden' name='action2' value='save'>";
        $h = $h . "<input type='hidden' name='id' value='$template'>";
        $h = $h . "<div><textarea name='content' style='width:600px;height:500px;'>".htmlspecialchars($content,ENT_QUOTES)."</textarea></div>";
        $h = $h . "<input class='btn btn-success btn-large' type='submit' value='submit'>";
        $h = $h . "</form>";


        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;
        exit;
        
} else if ( $action == "list-templates" ) {

		
        $action2 = $_POST["action2"];
        if ($action2 == "add") {
	        $DIR = $_POST["dir"];
	        $FILENAME = $_POST["filename"];
	        if ($FILENAME != "") {
				file_put_contents($DIR.$FILENAME,"");
	        }
        }

        $h = "";
        $h = $h . "<legend>".L('pagz')."</legend>";
        $h = $h . "<div class='btn-toolbar'>";
    	
        $h = $h . "</div>";		// end toolbar
        $h = $h . "<table class='table table-striped table-bordered table-condensed'>";
        $h = $h . "<tr>";
        $h = $h . "<th>".L('Title')."</th>";
        $h = $h . "<th>".L('options')."</th>";
        $h = $h . "</tr>";


		$dirs = array("templates/pages/","templates/messages/");
		//  <a class='btn btn-mini btn-success' style='margin-left:40px;' href='api.php?action=list-templates&action2=add'> <i class='icon-plus icon-white'></i> ADD</a>
		
		foreach ($dirs as $dir) {
			$h = $h . sprintf("<tr><td colspan='2'>Folder: %s</td></td></tr>",$dir);
			$files = scandir($dir);
			foreach ($files as $file) {
				if (strpos($file,".html") || strpos($file,".css")) {
					$h = $h . sprintf("<tr><td> --> %s</td><td>".B("edit","api.php?action=show-template&id=%s")."</td></tr>",$file,$dir.$file);
				}
			}
			$h = $h . sprintf("<tr><td colspan='2'><form action='api.php'><input type='hidden' name='action' value='list-templates'><input type='hidden' name='action2' value='add'><input type='hidden' name='dir' value='$dir'><input type='text' name='filename'><input type='submit' class='btn btn-success btn-mini value='add'></form></td></tr>",$dir);

		}

        $h = $h . "</table>";
        echo $htop;
        echo '<div class="plain-hero-unit">';
        echo $h;
        echo "</div>";
        echo $hbot;
        exit;


		
} else if ( $action == "do-reorder-pages" ){
	
	$db = mysql_connect($dbhost,$username,$password);
    mysql_select_db($database) or die("Unable to select database");
    mysql_query("SET NAMES utf8", $db);
    mysql_query( "SET CHARACTER SET utf8", $db );
	
	$h = "";
	
	//$CAT = $_POST['cat'];
	$CATID = $_POST['catid'];
	$ORDER = $_POST['order'];
	
	if ($ORDER && $ORDER != "") {
		$O = explode(",",$ORDER);
		$oc = 0;
		foreach ($O as $o){
			$sql = sprintf("update pages set CatID=%s, Volgorde=%s where id=%s",$CATID,$oc,$o);
			mysql_query($sql);
			$oc++;
		}
	}

    $retval = new obj();
    $retval->status = 0;
    $retval->statusMsg = sprintf("successfully re-ordered ");
    echo json_encode($retval);
	
	exit;

} else if ( $action == "do-reorder-cats" ){
	
	$db = mysql_connect($dbhost,$username,$password);
    mysql_select_db($database) or die("Unable to select database");
    mysql_query("SET NAMES utf8", $db);
    mysql_query( "SET CHARACTER SET utf8", $db );
	
	$h = "";
	
	$ORDER = $_POST['order'];
	
	if ($ORDER && $ORDER != "") {
		$O = explode(",",$ORDER);
		$oc = 0;
		foreach ($O as $o){
			$sql = sprintf("update ignore cats set Volgorde=%s where id=%s",$oc,$o);
			mysql_query($sql);
			$oc++;
		}
	}

    $retval = new obj();
    $retval->status = 0;
    $retval->statusMsg = sprintf("successfully re-ordered ");
    echo json_encode($retval);
	
	exit;

	
		
} else if ( $action == "do-volgorde" ){
	
	$db = mysql_connect($dbhost,$username,$password);
    mysql_select_db($database) or die("Unable to select database");
    mysql_query("SET NAMES utf8", $db);
    mysql_query( "SET CHARACTER SET utf8", $db );
	
	$h = "";
	
	//$CAT = $_POST['cat'];
	$CATID = $_POST['catid'];
	$ORDER = $_POST['order'];
	
	if ($ORDER && $ORDER != "") {
		$O = explode(",",$ORDER);
		$oc = 0;
		foreach ($O as $o){
			$sql = sprintf("update pages set Volgorde=%s where id=%s",$oc,$o);
			mysql_query($sql);
			$oc++;
//			$h = $h . $sql;
		}
	}
	
	
	$query="select * from cats";
	$result=mysql_query($query);
	for ($r1=0; $r1 < mysql_numrows($result); $r1++) {
		
		$h = $h . "<h4><a href='api.php?action=do-volgorde&catid=".mysql_result($result,$r1,"id")."'>".mysql_result($result,$r1,"Pagename")."</a></h4>";
		
	}
	
	if ( $CATID == "" ){
	
	} else{
		
		$query1="select * from pages where CatID=".$CATID." order by Volgorde";
		$result1=mysql_query($query1);
		
		$h = $h. "<br>";
	
		$h = $h. "<ol id='sortable'>";
		for ($r1=0; $r1 < mysql_numrows($result1); $r1++) {
		
			$h = $h. "<li id='".mysql_result($result1,$r1,"id")."' class='ui-state-default sort-item'>".mysql_result($result1,$r1,"Pagename")."</li>";
		
		}
		
		$h = $h. "</ol>";
		$h = $h. "<br>";
		$h = $h . "<a href='javascript:$"."push2press.getlist(".$CATID.");' class='btn btn-success'>OK</a>";
		$h = $h. "<br>";
		

	
	}
	
	echo $htop;
    echo '<div class="plain-hero-unit">';
    echo $h;
    echo "</div>";
    echo $hbot;

} else if ( $action == "show-page" ) {

        $db = mysql_connect($dbhost,$username,$password);
        mysql_select_db($database) or die("Unable to select database");
        mysql_query("SET NAMES utf8", $db);
        mysql_query( "SET CHARACTER SET utf8", $db );

        $id = $_POST["id"];
        $action2 = $_POST["action2"];
        $action3 = $_POST["action3"];
        $CatId = $_POST["CatId"];
        $h = "";

        $action2 = $_POST["action2"];
        if ($action2 == "add") {
                $query="insert ignore into pages (ID,CatID) values (0,'".$CatId."')";
                $result=mysql_query($query);
                $newid = mysql_insert_id();
                $action2 = "";
                $id = $newid;
		}
		
		
        if ($action2 == "upload") {

        		$target = $images_folder;

                $target = $target . basename( $_FILES['media']['name']) ;
                $ok=1;
                if(move_uploaded_file($_FILES['media']['tmp_name'], $target)) {
                        $db = mysql_connect($dbhost,$username,$password);
                        mysql_select_db($database) or die("Unable to select database");
                        mysql_query("SET NAMES utf8", $db);
                        mysql_query( "SET CHARACTER SET utf8", $db );

                        $img = basename( $_FILES['media']['name']);

                        $query="update ignore pages set img='".$img."' where id=" . $id;
                        $result=mysql_query($query);
                }
                
                
        }

        if ($action2 == "delete") {
                $query="delete ignore from pages where id=" . $id;
                $result=mysql_query($query);
                $h = "DELETED";
                $h = $h . "<a class='btn' href='api.php?action=list-pages'>OK <i class='icon-check'></i></a>";

                $MSG = "<div class='alert alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Deleted</div>";
				$action = "homepage";


        } else if ($action2 == "update") {
                $Pagename = $_POST["Pagename"];
                $Caption = $_POST["Caption"];
                $Volgorde = $_POST["Volgorde"];
                $CatID = $_POST["CatID"];
                $bodytext = $_POST["elm1"];
				$temp = $_POST["template"];
				$img = $_POST["img"];
				$type = $_POST["type"];
				$eData = $_POST["extraData"];
				$eData_navbar = $_POST["extraData_navbar"];
				if ($eData_navbar == "") $eData_navbar = "n";
				
				$ed = json_decode($eData);
				$ed->navBar = $eData_navbar;
				/*
				echo "<br>";
				echo "<br>";
				echo "<br>";
				echo "<p>";
				echo "<pre>";
				print_r($ed);
				echo "</pre>";
				echo "<pre>";
				echo json_encode($ed);
				echo "</pre>";
				echo "</p>";
				echo "<br>";
				echo "<br>";
				echo "<br>";
				*/
				$eData = json_encode($ed);

                $query="update ignore pages set Caption='".esc($Caption)."', Pagename='".esc($Pagename)."', Volgorde='".$Volgorde."', CatID='".$CatID."', bodytext='".esc($bodytext)."', template='".$temp."', img='".$img."', type='".$type."', extraData='".esc($eData)."' where id=" . $id;
                /*
				echo "<pre>";
				echo $query;
				echo "</pre>";
                */
                
                $result=mysql_query($query);
                $h = "UPDATED";
                
                $h = $h . "<h2>In order to update your app 'pull to refresh' the side menu down</h2>";
                $h = $h . "<img src='images/reload-push2press-diagram.jpg' width='900'><br>";
				
				
                $h = $h . "<a class='btn' href='api.php?action=show-page&id=" . $id . "'>OK <i class='icon-check'></i></a>";
                
                $MSG = "<div class='alert alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><h2>In order to update your app 'pull to refresh' the side menu down</h2><img src='images/reload-push2press-diagram.jpg' width='600'></div>";
				$action = "homepage";
                
				setConfiguration("isthismyfirsttime_ihaveeditednow","y");
                
                
        } else if ($action2 == "update"){
        
        	$Pagename = $_POST["Pagename"];
        	$Caption = $_POST["Caption"];
        	$Volgorde = $_POST["Volgorde"];
        	$CatID = $_POST["CatID"];
        	$bodytext = $_POST["elm1"];
        	$temp = $_POST["template"];
        	$img = $_POST["img"];
        	$type = $_POST["type"];
        	$eData = $_POST["extraData"];
        	
        	$query="update ignore pages set Caption='".esc($Caption)."', Pagename='".esc($Pagename)."', Volgorde='".$Volgorde."', CatID='".$CatID."', bodytext='".esc($bodytext)."', template='".$temp."', type='".$type."', img='".$img."', extraData='".esc($eData)."' where id=" . $id;
            $result=mysql_query($query);
            $h = "Updated!";
            $h = $h . "<a class='btn' href='api.php?action=show=page&id=".$id."'>OK <i class='icon-check'></i>";    
        
        } else {

        	$query="select * from pages where id=" . $id;
        	$result=mysql_query($query);

        	$h = "";
        	
        	if (mysql_numrows($result) > 0) {
                $r=0;

				/*
                $query1="select * from cats";
                $result1=mysql_query($query1);
                $sel = "<select name='CatID2'>";
                $sel = $sel . "<option value='0'>Geen</option>";
                for ($r1=0; $r1 < mysql_numrows($result1); $r1++) {
                        $selected = "";
                        $IID = mysql_result($result1,$r1,"ID");
                        $PGN = mysql_result($result1,$r1,"Pagename");

                        if ($IID == mysql_result($result,$r,"CatID")) $selected = "selected";
                        $sel = $sel . "<option value='" .$IID."' ".$selected.">".$PGN."</option>";
                }
                $sel = $sel . "</select>";
                */
                
				$catid = mysql_result($result,$r,"CatID");
				$PAGETYPE = mysql_result($result,$r,"type");
				$EXTRADATA = mysql_result($result,$r,"extraData");
				if ($EXTRADATA == "") {
					$EXTRADATA = '{ "navBar" : "y" }';
				}


				// cloud9 code editor
				$h .= "<script src='lib/cloud9/ace/src/ace.js' type='text/javascript' charset='utf-8'></script>";
				$h .= "<script src='lib/cloud9/ace/src/mode-javascript.js' type='text/javascript' charset='utf-8'></script>";

				// form
                $h = $h . "<form action='api.php' onSubmit='push2press.preparePageEditSubmit(this);' method='POST'>";
                $h = $h . "<input type='hidden' name='action' value='show-page'>";
                $h = $h . "<input type='hidden' name='action2' value='update'>";
                $h = $h . "<input type='hidden' name='id' value='".$id."'>";
//                $h = $h . "<input type='hidden' name='CatID' value='".$catid."'>";
                $h = $h . "<input type='hidden' name='type' id='page_type' value='".$PAGETYPE."'>";


                $img = mysql_result($result,$r,"img");
                $img_size = ($img && $img != "") ? sprintf("timthumb.php?h=32&w=32&src=%s",$img) : "";
				if (strpos($img_size,"client_images/images/icons/glyphicons")) $img_size = $img_size . "&zc=2&f=5,238,238,238,1";



//	        	$h .= "</div>";


				$dirs = array("plugins/connectors/","templates/messages/");

				$pagetypes = "var _pagetypes = [];\n";
				$pagetypes = $pagetypes . "_pagetypes.push({'p':'','i':'images/gwlogo.png','n':'WYSIWYG PAGE'});\n";
				$pagetypes = $pagetypes . "_pagetypes.push({'p':'TI','i':'images/gwlogo.png','n':'Titanium Code PAGE'});\n";

				$DIR = "plugins/connectors/";
				$dirs = scandir($DIR);
				foreach ($dirs as $dir) {
//       				$h = $h . "<option value='ti|".($DIR.$dir)."'>".($DIR.$dir)."</option>";

					$files = scandir($DIR.$dir."/");
					foreach ($files as $file) {
						if (strpos($file,".js")) {
							$meta = new obj();
							$meta->name = ($dir.$file);
							$meta->path = ($DIR.$dir."/".$file);
							
							$js_file_contents = file_get_contents($meta->path);
							foreach (explode("\n",$js_file_contents,20) as $js_file_contents_line) {
								if (strpos($js_file_contents_line,"wiz:#name:") === 0) {
									$meta->name = substr($js_file_contents_line,10);
								}
							}
							
//	        				$h = $h . "<option value='ti|".$meta->path."'>".$meta->name."</option>";
	        				
        					$TEXT =  str_replace(">", "&gt;" , $TEXT);

	        				$pagetypes = $pagetypes . "_pagetypes.push({'p':'ti|".$meta->path."','i':'".str_replace(".js",".png",$meta->path)."','n':'".$meta->name."'});\n";
						}
					}
				}
				

				/* wordpress link - lists all the available pages */				
				if ($hosted && $hosted == "wordpress") {
					$query1 = "select * from wp_posts where post_status='publish' and post_title!=''";
					$result1 = mysql_query($query1);
    				for ($i=0; $i < mysql_numrows($result1); $i++){
//        				$h = $h . "<option value='wp:".mysql_result($result1,$i,"ID")."'>".mysql_result($result1,$i,"post_title")."</option>";
//        				$pagetypes = $pagetypes . "_pagetypes.push({'p':'wp:".mysql_result($result1,$i,"ID")."','n':'wordpress link : ".mysql_result($result1,$i,"post_title")."'});\n";

						$wp_page_title = htmlspecialchars(mysql_result($result1,$i,"post_title"),ENT_QUOTES);
        				$pagetypes = $pagetypes . "_pagetypes.push({'p':'wp:".mysql_result($result1,$i,"ID")."','n':'wordpress link : ".$wp_page_title."'});\n";

    				}
				}
//				$h = $h . "</select>";
				$h = $h . "<script>push2press.setInitialPage_type('".$PAGETYPE."');</script>";
				$h = $h . "<script>".$pagetypes."</script>";
				


				$h .= "<legend>Push2Press Page Editor</legend>";

	        	$h .= "<div class='row-fluid'>";

	        	$h .= "<div class='span7 editbox' style='padding:0;'>"; // box
				
				$h .= "<div id='wysiwygbox'>"; 
                $h = $h . "<div id='topSpace-wrapper' style='background-color:#333333;'>"; // wrapper
                $h = $h . "<div id='topSpace'></div>";
                $h = $h . "<div style='width:300px;xfloat:left;' class='xeditbox' id='bottomSpace'>"; // bottomspace

				$h = $h . "<textarea class='xckeditor' id='elm12' name='elm1' rows='15' cols='80' style='width: 90%' class='tinymce'>". textareaSafe(mysql_result($result,$r,'bodytext')) ."</textarea class='ckeditor'>";
				

				$h = $h . "</div>";	// bottomSpace
				$h = $h . "</div>"; // topspace-wrapper
				$h = $h . "</div>"; // wysiwygbox

				// editbox - code - start
				$h .= '<div id="xx-p2p-ace-tr" style="display:none;">';
				$h .= '<pre id="editor"></pre>';
				$h .= '</div>';
				// editbox - code - end



				$h .= "</div>"; // box

				//$h .= "<style>body { background-color : #eee; } </style>";


				// editbox - info and tamplate start
	        	$h .= "<div class='span5 editbox'>";
               	$h .= "<label class='editbox_head'>Publish</label>";

//	        	$h .= "<h2>Showing Page #".$id."</h2>";
//	        	$h .= "<div>This is a {{type}} page based on {{yy}}</div>";
	        	
				$h .= "<a class='btn' href='javascript:push2press.choosePageType();'>Replace Content Using Wizzard</a>";
//				$h .= "<a class='button' href='javascript:push2press.editwithace();'>ace</a>";
//				$h .= "<a class='button' href='javascript:push2press.backwithace();'>ace2</a>";
//				$h .= "<a class='button' href='javascript:push2press.toolbars();'>tools</a>";
//				$h .= "<a class='button' href='javascript:push2press.insertimage();'>II</a>";
	        	
	        	$h .= "<br>";
				$h .= "<div class='editbox_foot'><input class='btn btn-info' type='submit' value='Update'></div>";

				$h .= "</div>"; // box
				// editbox - template - end




    			// editbox - image - start
	        	$h .= "<div class='span5 editbox'>"; 
                	$h .= "<label class='editbox_head'>How the page appears in the menu</label>";

                	$h .= "<div class='instructions'>The page you are editing will also appear in the side menu of the app together with an icon similar to the example below.  You can edit the title by clicking on the text and change the icon using the button to open your media library</div>";
                	
                	$h .= "<div id='test' class='previewimage'>";
                	$h .= "<input name='img' id='main_img_fld' type='hidden' value='" . $img . "'>";
                	$h .= "<img id='main_img' src='" . $img_size . "' height=32 width=32>";


                	$h .= " <input name='Pagename' class='reverse-input' type='text' value='" . htmlspecialchars(mysql_result($result,$r,"Pagename"),ENT_QUOTES) . "'> ";

                	$h .= "&nbsp;&nbsp;&nbsp;";
                	$h .= "<a class='btn pull-right' href='javascript:troller();'>".L("ChangeIcon")."</a>";
                	$h .= "</div>";

//                	$h .= " <input name='Pagename' type='text' value='" . htmlspecialchars(mysql_result($result,$r,"Pagename"),ENT_QUOTES) . "'> ";
                
                $h .= "</div>"; // box
    			// editbox - image - end


				// editbox - extra data - start
	        	$h .= "<div class='span5 editbox'>";
               	$h .= "<label class='editbox_head'>Page Options</label>";


				$h = $h . "<label>".L("template")."<label>";
				
				require ("templates/pages/_templates.php");
				$h = $h . "<select name='template'>";
				foreach ($_page_templates as $key=>$data) {
					$selected = (mysql_result($result,$r,"template") == $key) ? "selected" : "";
					$h = $h . sprintf("<option value='%s' %s>%s</option>",$key,$selected,$data);
				}
				$h = $h . "</select>";

				$ed = json_decode($EXTRADATA);

//				$h .= "<div style='display:none'><label>".L("extraData")."</label><textarea name='extraData'>" . $EXTRADATA . "</textarea></div>";
				$h .= "<label>&nbsp;</label><input type='checkbox' value='y' name='extraData_navbar' ".(($ed->navBar == "y") ? "checked" : "")."><span> Display Nav Bar</span>";
/*
$h .= '
	<script src="api-editpage-ang.js"></script>
    <link rel="stylesheet" href="api-editpage-ang.css">
    <div ng-controller="TodoCtrl">
      <span>{{remaining()}} of {{todos.length}} remaining</span>
      [ <a href="" ng-click="archive()">archive</a> ]
      <ul class="unstyled">
        <li ng-repeat="todo in todos">
          <input type="checkbox" ng-model="todo.done">
          <span class="done-{{todo.done}}">{{todo.text}}</span>
        </li>
      </ul>
      <form ng-submit="addTodo()">
        <input type="text" ng-model="todoText"  size="30"
               placeholder="add new todo here">
        <input class="btn-primary" type="submit" value="add">
      </form>
    </div>';
*/



               $query111="select * from cats";
                $result111=mysql_query($query111);
                $sel111 = "<select name='CatID'>";
                $sel111 = $sel111 . "<option value='0'>Geen</option>";
                for ($r111=0; $r111 < mysql_numrows($result111); $r111++) {
                        $selected = "";
                        $IID111 = mysql_result($result111,$r111,"ID");
                        $PGN111 = mysql_result($result111,$r111,"Pagename");

                        if ($IID111 == $catid) $selected111 = "selected"; else $selected111 = "";
                        $sel111 .=  "<option value='" .$IID111."' ".$selected111.">".$PGN111."</option>";
                }
                $sel111 .= "</select>";



				$h .= "<label>Category</label>".$sel111;	

				$h .= "<div><label>ADVANCED SETUP</label><textarea name='extraData'>" . $EXTRADATA . "</textarea></div>";


				$h .= "</div>";	// box
				// editbox - extra data - end



				// editbox - submit - start
	        	//$h .= "<div class='span5 editbox'>";
                //$h .= "<label>&nbsp;</label><input class='btn btn-success' type='submit'>";
	        	//$h .= "</div>";
				// editbox - submit - end



				$h .= "</div>"; // row


				
								
				
//	        	$h .= "<div class='row-fluid'>";
//	        	$h .= "<div class='span6 editbox'>";
//                $h = $h . "<label>".L("CatID")."</label>" . $sel . "";
//	        	$h .= "</div>";


				$h = $h . "\n";
				$h = $h .'<script type="text/javascript">';
				$h = $h . "\n";
/*
				$h = $h . "function change_page_type() {";
				$h = $h . "	var opt = jq('#pagetype0').val();";
				$h = $h . " if (opt && opt != '') {";
				$h = $h . "		console.log(pagenames);";
				$h = $h . " 	var pgtype = pagenames[opt];";
				$h = $h . "		console.log(pgtype);";
				$h = $h . "	}";
				$h = $h . "	console.log(opt);";
				$h = $h . "}";
*/				
				$h = $h . "\n";
//				$h = $h . "push2press.selectAppropriateEditorOnReady('".$PAGETYPE."');";
				$h = $h . "push2press.selectAppropriateEditor('".$PAGETYPE."');";
				$h = $h . "\n";
				//$h = $h . "push2press.edit_with_wysiwyg();";
				//$h = $h . "push2press.selectAppropriateEditor('".$PAGETYPE."');";
				$h = $h .'</script>';
				$h = $h . "\n";

                
                $h = $h . "</form>";
                $h .= "</div>";
                

                $h = $h . "<form action='api.php'>";
                $h = $h . "<input type='hidden' name='action' value='show-page'>";
                $h = $h . "<input type='hidden' name='action2' value='delete'>";
                $h = $h . "<input type='hidden' name='id' value='".$id."'>";
                $h = $h . "<input class='btn btn-danger btn-large' type='submit' value='delete'>";
                $h = $h . "</form>";

				$h = $h .'<script type="text/javascript" src="http://www.google.com/jsapi"></script>';
				$h = $h . "\n";
				$h = $h .'<script type="text/javascript">';
				$h = $h . "\n";
				$h = $h .'	google.load("jquery", "1");';
				$h = $h . "\n";
				$h = $h .'</script>';
				$h = $h . "\n";
				$h = $h .'<script type="text/javascript" src="jscripts/tiny_mce/jquery.tinymce.js"></script>';
				$h = $h . "\n";
				/*
				$h = $h .'<script type="text/javascript">';
				$h = $h . "\n";
				$h = $h .'	$().ready(function() {';
				$h = $h ."		$('textarea class='ckeditor'.tinymce').tinymce({";
				$h = $h .'			// Location of TinyMCE script';
				$h = $h .'			script_url : "jscripts/tiny_mce/tiny_mce.js",';
				$h = $h .'			theme : "advanced",';
				$h = $h .'			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",';
		
				$h = $h .'			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",';
				$h = $h .'			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",';
				$h = $h .'			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",';
				$h = $h .'			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",';
				$h = $h .'			theme_advanced_toolbar_location : "top",';
				$h = $h .'			theme_advanced_toolbar_align : "left",';
				$h = $h .'			theme_advanced_statusbar_location : "bottom",';
				$h = $h .'			theme_advanced_resizing : true,';

				$h = $h .'			//content_css : "css/content.css",';
				$h = $h .'			}';
				$h = $h .'		});';
				$h = $h .'	});';
				$h = $h .'</script>';
				*/
				
				if ($action3 && $action3 == "wizard") {
				$h = $h . "<script>$"."(function() { push2press.choosePageType();});</script>";
				}
				
        	}

	        echo $htop;
	        echo '<div class="plain-hero-unit" ng-app>';
	        echo "<div class='container-fluid'>";
	        echo $h;
	        echo "</div>";
	        echo "</div>";
	        echo $hbot;
        }

} else if( $action == "wp" ){

/*
	require_once('ripcord-1.1/ripcord.php');
    $client = ripcord::client( 'http://www.parkshark.eu/xmlrpc.php' );
    $score = $client->wp->getPosts();
    var_dump($score);
*/
} else if( $action == "wp2" ){
	
	require 'atom.php';

} else if( $action == "cron" ){

	

} else {
	$emenu = "";
	require_once './local_functions.php';
	$action = "homepage";
}
if ($action == "homepage") {

	$h = "";
	$emaillinkto = $_GET["emaillinkto"];
	$emailadminlinkto = $_GET["emailadminlinkto"];
		
	if ($emaillinkto != "") {
		$message = sprintf("Link to push2press page is <a href=\"push2press://?url=%s\">%s</a> ",getConfiguration("url",""),getConfiguration("sitename",""));
		$messageplain = sprintf("Link to push2press page is &lt;a href=\"push2press://?url=%s\"&gt;%s&lt;/a&gt; ",getConfiguration("url",""),getConfiguration("sitename",""));
		$msuccess = mail($emaillinkto, 'Push2press Email Link', $message,$headers);
		$h = sprintf("<div> Email sent to %s %s </div>",$emaillinkto,$msuccess);
		if ($msuccess === false) {
			$h = sprintf("<div> There was a sending error to %s - send yourself this link <pre>%s</pre></div>",$emaillinkto,$messageplain);
		}
	}
	if ($emailadminlinkto != "") {
		$message = sprintf("Link to you new site is %s/api.php. <br> Link to push2press page is <a href=\"push2press://?url=%s\">%s</a> ",getConfiguration("url",""),getConfiguration("url",""),getConfiguration("sitename",""));
		$messageplain = sprintf("Link to you new site is %s/api.php. Link to push2press page is &lt;a href=\"push2press://?url=%s\"&gt;%s&lt;/a&gt; ",getConfiguration("url",""),getConfiguration("url",""),getConfiguration("sitename",""));
		$msuccess = mail($emailadminlinkto, 'Push2press Email Link', $message,$headers);
		$h = sprintf("<div> Email sent to %s %s </div>",$emailadminlinkto,$msuccess);
		
		
		$h = "";
		require_once("api-newsite.php");
		$h .= $hh;
		
		if ($msuccess === false) {
				$h = sprintf("<div> There was a sending error to %s - send yourself this link <pre>%s</pre></div>",$emailadminlinkto,$messageplain);
		}			
	}
	
                
	if (getConfiguration("isthismyfirsttime_ihaveeditednow","n") == "y") {
		if (getConfiguration("isthismyfirsttime","") != "n") {
			require("api-newedit.php");
			$h .= $hh;
			setConfiguration("isthismyfirsttime","n");
		}
	}
	
	
	

//	require("api-newedit.php");
//	require("api-newsite.php");
//	$h .= $hh;
	
	
	$c1 = sqlcount("select count(*) as c from message");
    $c2 = sqlcount("select count(*) as c from log_phone where uid != ''");
    
    $h = $h . '<link href="github/ribbons.css" rel="stylesheet" type="text/css" />';
    $h = $h . "<br>";
    $h = $h . "<br>";
    $h = $h . '<div class="container">';
    $h = $h . '      <div class="row-fluid">';
    $h = $h . '			<div class="span5">';
    
    $db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
    mysql_query("SET NAMES utf8", $db);
    mysql_query( "SET CHARACTER SET utf8", $db );

    $query="select *,c.Pagename as CatName, c.img as CatImage, c.Caption as CatCaption from pages p left join cats c on (p.CatID = c.id) order by c.Volgorde,p.Volgorde";
    $result=mysql_query($query);



        
	$h = $h . '<link href="api-menu.css" rel="stylesheet">';   
	$h = $h . '<link href="api-deniz.css" rel="stylesheet">';    
	$h = $h . "<div class='content-tree'>";
	
	$welcome = "";
	if ($c2 > -1) {
		$welcome = $welcome . "<h3> Welcome back to push2press</h3>";
	    $welcome = $welcome . $MSG;
	} else {
	    $welcome = $welcome . "<h3> Welcome to your push2press site</h3>";
	    $welcome = $welcome . $MSG;
	}
    
    $h = $h . '	<div class="xcontainer navbar" style="margin-left:25px;margin-right:25px;">';
	$h = $h . '		<br>';
	$h = $h . '		<br>';
	$h = $h . '		<div class="mic"></div>';
	$h = $h . '		<br>';
	$h = $h . '		<br>';
	$h = $h . '		<img src="images/app-logo-reverse.png">';
	$h = $h . '		<ul class="nav pull-right" style="margin:14px 0 0 0;">';
	$h = $h . '			  <li class="dropdown">';
	$h = $h . '	    		<a href="api.php?action=show-cat&action2=add" class="dropdown-toggle" data-toggle="dropdown">';
	$h = $h . '	    			ADD CATEGORY';
	$h = $h . '	    		</a>';
	$h = $h . '	  		  </li>';
	$h = $h . '		</ul>';
	$h = $h . '	</div>';
	
	$h = $h . '	<div style="min-height:480px;margin:0 25px;background-color:#15171a;">';
	
    $h = $h . "<ol class='nested_with_switch vertical x-root' style='margin:0px;'>";
    $CurrentCatID = -1;
    $CurrentCatEndBlock = "";
    for ($r=0; $r < mysql_numrows($result); $r++) {
    	if ($CurrentCatID != mysql_result($result,$r,"CatID")) {
        $nodrag = "";
        	if ( mysql_result($result,$r,"CatID") == "") {
        		$nodrag = " x-nodrag";
        	}
        	$h = $h . $CurrentCatEndBlock;
        	$h = $h . "<li id='push2press-tree-cat-".mysql_result($result,$r,"CatID")."' class='x-category sort-item".$nodrag."'>";
        	$h = $h . "<div class='x-div' id='x-normal-page' style='height:50px;'>";
    		if ($nodrag == "") {
    			//$h = $h . "<i class='icon-move icon-white' style='font-size:18px;'></i>";
	        }
	                
			$h = $h . "<span>" . mysql_result($result,$r,"CatName") . "</span>";
			$h = $h . "<span class='xx-edit-over' style='display:none;'>";
			$h = $h . "<div class='btn-group pull-right'>";
			$h = $h . "<img src='images/p2p-icons/arrow_two_head_icon&48-ccw.png' width='16'>";
	   		$h = $h . "&nbsp;&nbsp;";
	        //$h = $h . "<span><a class='btn btn-mini xbtn-success' href='api.php?action=show-cat&id=" . mysql_result($result,$r,"CatID") . "'><img src='images/p2p-icons-black/doc_edit_icon&48.png' width='12'> CHANGE NAME</a></span>";
	        $h = $h . "<span><a class='x-phone-btn-grey' href='api.php?action=show-cat&id=" . mysql_result($result,$r,"CatID") . "'>CHANGE NAME</a></span>";
			$h = $h . "&nbsp;&nbsp;";
			//$h = $h . "<span><a class='btn btn-mini btn-success' href='api.php?action=show-page&action2=add&action3=wizard&CatId=".mysql_result($result,$r,"CatID")."'> <i class='icon-plus icon-white'></i> ADD PAGE</a></span>";
			$h = $h . "<span><a class='x-phone-btn-grey' href='api.php?action=show-page&action2=add&action3=wizard&CatId=".mysql_result($result,$r,"CatID")."'>ADD PAGE</a></span>";
	        $h = $h . "</div>";
			$h = $h . "<span/>";
			$h = $h . "</div>";
	        $h = $h . "<ol id='push2press-tree-cat-ol-".mysql_result($result,$r,"CatID")."' class='x-category'>";
	        $CurrentCatID = mysql_result($result,$r,"CatID");
	        $CurrentCatEndBlock = "</ol></li>";
		}
                
        $h = $h . "<li id='push2press-tree-page-".mysql_result($result,$r,"id")."' class='x-page ui-state-default sort-item'>";
       	$h = $h . "<div style='height:50px;'>";
       	//$h = $h . "<div class='pull-left'><i class='icon-move icon-white' style='font-size:18px;'></i></div>";

        $img = mysql_result($result,$r,"img");
        $img_size = ($img && $img != "") ? sprintf("timthumb.php?h=32&w=32&src=%s",$img) : "";
		if (strpos($img_size,"client_images/images/icons/glyphicons")) $img_size = $img_size . "&zc=2&f=5,238,238,238,1";

		$h = $h . "<div class='pull-left page-icon'  ><img height='32' width='32' src='" . $img_size . "' style='margin-top:9px;'></div>";

		$h = $h . "<span class='page-title'>";
        $h = $h . "<span>" . mysql_result($result,$r,"Pagename") . "</span>";
        //$h = $h . "<span>" . mysql_result($result,$r,"Caption") . "</span>";
        $h = $h . "</span>";

		/*$h = $h . "<div class='btn-group pull-right' style=''>";
        $h = $h . "<span class='xx-edit-butts'>" .B("edit-txt","api.php?action=show-page&id=" . mysql_result($result,$r,"id") . ""). "";
        $h = $h . "" .B("preview-txt","javascript:\$push2press.previewpage(\"api.php?action=get-page&id=" . mysql_result($result,$r,"id")."\");"). "</span>";
       	$h = $h . "</div>";*/
       	$h = $h . "<span class='xx-edit-over-pages' style='display:none;'>";
		$h = $h . "<div class='btn-group pull-right'>";
		$h = $h . "<img src='images/p2p-icons/arrow_two_head_icon&48-ccw.png' width='16'>";
	   	$h = $h . "&nbsp;&nbsp;";
	    //$h = $h . "<span><a class='btn btn-mini xbtn-success' href='api.php?action=show-page&id=" . mysql_result($result,$r,"id") . "'><img src='images/p2p-icons-black/doc_edit_icon&48.png' width='12'> EDIT PAGE</a></span>";
	    $h = $h . "<span><a class='x-phone-btn-grey' href='api.php?action=show-page&id=" . mysql_result($result,$r,"id") . "'>EDIT PAGE</a></span>";
		$h = $h . "&nbsp;&nbsp;";
		//$h = $h . "<span><a class='btn btn-mini xbtn-success' href='javascript:\$push2press.previewpage(\"api.php?action=get-page&id=" . mysql_result($result,$r,"id")."\");'>PREVIEW</a></span>";
		$h = $h . "<span><a class='x-phone-btn-grey' href='javascript:\$push2press.previewpage(\"api.php?action=get-page&id=" . mysql_result($result,$r,"id")."\");'>PREVIEW</a></span>";
	    $h = $h . "</div>";
		$h = $h . "<span/>";
       			
       	$h = $h . "</div>";
       	//$h = $h . "<div class='x-divider'></div>";

		$h = $h . "</li>";
                
    }
		
	$h = $h . $CurrentCatEndBlock;
	$h = $h . "</ol>";
	$h = $h . "<div class='x-div x-div-cat' style='height:50px;'>";
	$h = $h . "<span class='page-titlex'>";
	$h = $h . "Messages and settings";
	$h = $h . "</span>";
	$h = $h . "</div>";

	$h = $h . "<a href='api.php?action=list-draft-mes'><div class='x-div x-div-page' style='height:50px;'>";
	$h = $h . "<img src='images/p2p-icons/mail_2_icon&48.png' width='26' class='x-edit-icon'>";
	$h = $h . "<span class='page-title'>";
	$h = $h . "Messages";
	$h = $h . "</span>";
	/*$h = $h . "<div class='btn-group pull-right'>";
	$h = $h . "<span>" .B("edit","api.php?action=list-draft-mes"). "";
	$h = $h . "</div>";*/
	$h = $h . "</div></a>";

	$h = $h . "<a href='api.php?action=list-group'><div class='x-div x-div-page' style='height:50px;'>";
	$h = $h . "<img src='images/p2p-icons/wrench_plus_2_icon&48.png' width='26' class='x-edit-icon'>";
	$h = $h . "<span class='page-title'>";
	$h = $h . "Settings";
	$h = $h . "</span>";
	/*$h = $h . "<div class='btn-group pull-right'>";
	$h = $h . "<span>" .B("edit","api.php?action=list-group"). "";
	$h = $h . "</div>";*/
	$h = $h . "</div></a>";
	$h = $h . "</div>";//this is the actual end of the placeholding min-height thing	
	$h .= "<br>";
	$h .= "<div class='button'><div class='buttonbox'></div></div>";
	$h .= "<br>";
	$h = $h . "</div>";
	$h = $h . "</div>";
    
?>
	<script>
	
	function loaderfunction(){
		
		var lines = $(".x-page");
		for (var i=0; i < lines.length; i++) {
			$(lines[i]).bind("mouseenter", function() {
				$(this).find(".xx-edit-over-pages").css("display","block");
				$(this).find(".xx-edit-over-pages").css("margin-top","-50px");
			})
			$(lines[i]).bind("mouseleave", function() {
				$(this).find(".xx-edit-over-pages").css("display","none");
			})
		}
		
		var linesx = $("#x-normal-page");
		for (var r=0; r < linesx.length; r++) {
			$(linesx[r]).bind("mouseenter", function() {
				$(this).find(".xx-edit-over").css("display","block");
				$(this).find(".xx-edit-over").css("margin-top","-50px");
			})
			$(linesx[r]).bind("mouseleave", function() {
				$(this).find(".xx-edit-over").css("display","none");
			})
		}
		
	}	
	
	</script>
	<style>
		#xx-p2p-topnavbar {
			display : none;
		}
	</style>
	
<?php


	$query99="select * from log_phone";
	$result99=mysql_query($query99);
	
	$query100="select * from pages";
	$result100=mysql_query($query100);
	
	$count99 = mysql_numrows($result99);
	$count100 = mysql_numrows($result100);
	
    $h = $h . '<div class="span6">';
    $h = $h . '		<h2 class="x-dash-h2">Your personal dashboard</h2>';
    
    $bgcolor99 = "";
    $color99 = "";
    $bgcolor100 = "";
    $color100 = "";
    
    if ($count99 < 2){
    	$bgcolor99 = "background-color: #217cb5;";
    	$color99 = "color: #217cb5;";
    } else if ( $count99 > 1 && $count100 > 1){
    	$bgcolor100 = "background-color: #217cb5;";
    	$color100 = "color: #217cb5;";
    }
    
    $h = $h . '		<div class="x-dash-imp-grey-div">';
    $h = $h . '			<img src="images/p2p-icons-black/get-started.png" class="x-dash-imp-grey-img">';
    $h = $h . '			<div class="x-dash-imp-grey-txt">';
    $h = $h . '				<h3 class="x-dash-imp-grey-title" style="'.$color99.'">Download the app to GET STARTED</h3>';
    $h = $h . '				<p class="x-dash-imp-grey-text">To be able to view your app you will have to download the previewer app for your device here</p>';
    $h = $h . '			</div>';
    $h = $h . '			<div class="x-dash-imp-grey-buttons">';
    $h = $h . '				<a class="x-dash-imp-grey-button" href="javascript:push2press.modal(\'#gettheapp\');"  style="'.$bgcolor99.'">IPHONE</a>';
    $h = $h . '				'; 
    $h = $h . '				<a class="x-dash-imp-grey-button" href="javascript:push2press.modal(\'#gettheapp\');"  style="'.$bgcolor99.'">ANDROID</a>';
    $h = $h . '			</div>';
    $h = $h . '		</div>';
    
    $h = $h .'<div id="gettheapp" style="display:none;">';
	$h = $h .'			<div class="p2p-youareusing">';
	$h = $h .'				<table width="80%">';
	$h = $h .'				<tr><td colspan="2"><img src="images/application-logo.png"></td></tr>';
	$h = $h .'				<tr><td width="50%" valign="top">';
	$h = $h .'				<img src="images/MainImage.jpg" width=150>';
	$h = $h .'				<p>The preview App is available in the App Store</p>';
	$h = $h .'				<a href="push2press://?url='.getConfiguration("url","").'">';
	$h = $h .'					<img src="http://blog.eventphant.com/wp-content/uploads/2012/07/Apple-App-Store.jpg" height="50">';
	$h = $h .'				</a>';
	$h = $h .'				<br>';
	$h = $h .'				<p>The Android version is not working with thie version of Push2Press but will be available in the coming days/p>';
	$h = $h .'				<br>';

	$h = $h .'				</td><td width="50%" valign="top">';
	$h = $h .'				<p>In order to perform a quick setup click the QR code link below and follow the on screen instructions:</p>';
	$h = $h .'				<br>';
	$h = $h .'				<br>';
	$h = $h .'				<span id="qrcodesmall">';
	$h = $h .'				<a href="javascript:push2press.qrcode();">';	
	$h = $h .'				<img src="http://api.qrserver.com/v1/create-qr-code/?data='.urlencode("push2press://?url=".getConfiguration("url","")).'&size=250x250">';
	$h = $h .'				</a>';
	$h = $h .'				</span>';
	$h = $h .'				</td>';
	$h = $h .'			</tr>';
	$h = $h .'		</table>';
	$h = $h .'	</div>';
	$h = $h .'</div>';
    
    $h = $h . '		<div class="x-dash-imp-grey-div">';
    $h = $h . '			<img src="images/p2p-icons-black/send-pusher.png" class="x-dash-imp-grey-img">';
    $h = $h . '			<div class="x-dash-imp-grey-txt">';
    $h = $h . '				<h3 class="x-dash-imp-grey-title" style="'.$color100.'">send a push notification</h3>';
    $h = $h . '				<p class="x-dash-imp-grey-text">Here you can create user groups, view your members and, of course, <strong>send push notificatons</strong></p>';
    $h = $h . '			</div>';
    $h = $h . '			<div class="x-dash-imp-grey-buttons">';
    $h = $h . '				<a class="x-dash-imp-grey-button-double" href="api.php?action=list-log"  style="'.$bgcolor100.'" href="?action=list-log">TRY NOW!</a>';
    $h = $h . '			</div>';
    $h = $h . '		</div>';
    
    $h = $h . '		<legend class="x-dash-h2">Widgets</legend>';
    
    $h = $h . '		<div class="x-dash-widgets-outer">';
    $h = $h . '			<div class="x-dash-widgets-inner"><a href="javascript:kcnew();"><img src="images/p2p-icons-black/media.png" height="40" class="x-dash-imp-widgets-inner-img"></a><p>MEDIA LIBRARY</p></div>';
    $h = $h . '			<div class="x-dash-widgets-inner"><a href="javascript:push2press.modal(\'#youareusing\');"><img src="images/p2p-icons-black/about.png" height="40" class="x-dash-imp-widgets-inner-img"></a><p>ABOUT</p><div id="youareusing" style="display:none;"><li class="p2p-youareusing"> <img src="images/application-logo.png"><br><b>By Glimworm IT BV</b><br>Version : ' . $push2version["major"] . ' ' . $push2version["type"]. ' <br> Build : ' . $push2version["build"] . '</li></div></div>';
    $h = $h . '			<div class="x-dash-widgets-inner"><a href="api.php?action=list-dom"><img src="images/p2p-icons-black/settings.png" height="40" class="x-dash-imp-widgets-inner-img"></a><p>SETTINGS</p></div>';
    $h = $h . '			<div class="x-dash-widgets-inner x-dash-widgets-inner-last"><a href="http://push2press.uservoice.com/knowledgebase" target="new"><img src="images/p2p-icons-black/help.png" height="40" class="x-dash-imp-widgets-inner-img"></a><p>HELP</p></div>';
    
    /*custom things*/
    
    	if ($emenu != ""){
    		$h = $h . $emenu;
    	}
    
    /*end of custom things*/
    
    $h = $h . '			<div class="x-dash-widgets-inner x-dash-widgets-inner-last"><a href="api.php?action=logout" target="new"><img src="images/p2p-icons-black/help.png" height="40" class="x-dash-imp-widgets-inner-img"></a><p>LOGOUT</p></div>';
    
    $h = $h . '		</div>';
    
    $h = $h . '</div>';
    $h = $h . '</div>';
    
    echo $htoppopup;
    echo $htop;
    echo $h;
    echo $hbot;  
        
}
/* thans to

http://travishorn.com/jquery-filterList/

http://johnny.github.io/jquery-sortable/

http://kcfinder.sunhater.com/

*/
?>
