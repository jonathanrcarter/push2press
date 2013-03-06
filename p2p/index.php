<?php

header("HTTP/1.1 301 Moved Permanently");
header("Location: api.php"); 
exit;


require_once './lang/en.php';

$dbhost = "";
$username = "";
$password = "";
$database = "";
$images_folder = "";
$BASEPATH = "";

require_once './local_config.php';

mysql_connect($dbhost,$username,$password);
mysql_select_db($database) or die("Unable to select database");

$request = $_SERVER['REQUEST_URI'];
$request_parts = explode('/',$request);
$PART = $request_parts[1];

//echo $PART;

if ($PART == "" || $PART == "1"){

$_GET['action'] = "get-page";
$_GET['id'] = "1";
require ("./api.php");

} else if ($PART == "login"){
	
$_GET['action'] = "";
require ("./api.php");
	
} else{
	$query="select * from gwSEO where shtml = '".$PART."'";
	$result=mysql_query($query);
	for ($r=0; $r < mysql_numrows($result); $r++) {
		$url = mysql_result($result,$r,"pid");
	}
	$_GET['action'] = "get-page";
	$_GET['id'] = $url;
	require ("./api.php");
	
}


//header ("Location: $url", false);

?>
