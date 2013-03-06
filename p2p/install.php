<?php


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
$htop = $htop .'    <link href="http://www.push2press.com/p2p/api.css" rel="stylesheet">';
$htop = $htop .'  </head>';
$htop = $htop .'  <body>';
$htop = $htop .'    <div class="container">';

$hbot = "";
$hbot = $hbot .'      <footer>';
$hbot = $hbot .'        <p>&copy; Glimworm 2012</p>';
$hbot = $hbot .'      </footer>';
$hbot = $hbot .'      </div>';
$hbot = $hbot .'  </body>';
$hbot = $hbot .'</html>';

echo $htop;
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo "<img src='http://www.push2press.com/p2p/images/application-logo.png'></p>";
//echo dirname(__FILE__);

echo "<p>downloading version from github ... success</p>";

$download = file_put_contents(dirname(__FILE__)."/upgrade.zip", file_get_contents("https://github.com/jonathanrcarter/push2press/archive/master.zip"));

if ($download == false) {
	echo "exiting, error downloading code";
	exit;
}

$download = file_put_contents(dirname(__FILE__)."/pclzip.lib.php", file_get_contents("https://raw.github.com/jonathanrcarter/push2press/master/p2p/pclzip-2-8-2/pclzip.lib.php"));

if ($download == false) {
	echo "exiting, error downloading code";
	exit;
}


require_once(dirname(__FILE__).'/pclzip.lib.php');
$archive = new PclZip(dirname(__FILE__).'/upgrade.zip');

//$list = $archive->listContent();
//var_dump($list);

if ($archive->extract(PCLZIP_OPT_PATH, dirname(__FILE__)."", PCLZIP_OPT_REMOVE_PATH, "push2press-master/p2p",PCLZIP_OPT_REPLACE_NEWER) == 0) {
	echo "exiting, error downloading code";
	exit;
}

require(dirname(__FILE__).'/local_config.php');
$_frame = $_GET["_frame"];

if ($hosted && $hosted == "wordpress" && $frame != "y") {
	echo "<div><a class='btn btn-success' href='/wp-admin/admin.php?page=push2press/admin.php'>You can proceed to set up your site by clicking here</a></div>";
} else if ($frame == "y") {
	echo "<script>parent.p2p_admin();</script>";
	echo "<div><a class='btn btn-success' href='javascript:parent.p2p_admin();'>You can proceed to set up your site by clicking here</a></div>";
} else {
	echo "<div><a class='btn btn-success' href='api.php'>You can proceed to set up your site by clicking here</a></div>";
}

echo $hbot;



?>