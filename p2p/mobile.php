<?php

session_start();
$dbhost = "";
$username = "";
$password = "";
$database = "";
$images_folder = "";
$BASEPATH = "";
$MASTER_PASSWORD="";

require './local_config.php';

class obj {
}

	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );

	$query="select * from cats";
	$result=mysql_query($query);
	
    $retval = new obj();
	$retval->cats = array();
	$retval->config = array();
	$retval->config2 = array();
	
	for ($r=0; $r < mysql_numrows($result); $r++) {
		
		$cat = new obj();
		$cat->id = mysql_result($result,$r,"id");
		$cat->Pagename = mysql_result($result,$r,"Pagename");
		$cat->img = mysql_result($result,$r,"img");
		$cat->pages = array();
			
		$query2="select * from pages where CatID = " . $cat->id ;
		$result2=mysql_query($query2);
		for ($r2=0; $r2 < mysql_numrows($result2); $r2++) {
			$page = new obj();
			$page->id = mysql_result($result2,$r2,"id");
			$page->Pagename = mysql_result($result2,$r2,"Pagename");
				
			array_push($cat->pages,$page);
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
	
	
	$h = "";
//	$h = $h . "<h1>Menu</h1>";
	$h = $h . "<ul>";
	$h = $h . "<li><a href='javascript:menu_close();'>CLOSE</a></li>";
	foreach($retval->cats as $cat) {
		$cnt = 0;
		foreach($cat->pages as $page) {
			if ($cnt == 0) {
				$img = "<img width='24' height='24' src='http://www.clker.com/cliparts/D/c/K/g/I/l/white-square.svg' style=\"-webkit-mask-box-image: url('".$cat->img."');vertical-align:middle;\">";
				
				$h = $h . "<li class='cat'>&nbsp;".$img."&nbsp;<a href='api.php?action=get-page&id=".$page->id."'>". $cat->Pagename. "</a></li>";
				
//				$h = $h . "<li><h1><img src='".$cat->img."'></h1></li>";
//				$h = $h . "<li></li>";
				
			} else {
				$h = $h . "<li class='page'><a href='api.php?action=get-page&id=".$page->id."'>". $page->Pagename. "</a></li>";
			}
			$cnt++;
		}
	}
	$h = $h . "</li>";
	

?>
<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="utf-8">
	<meta name="description" content="{vars.description}">
	<meta http-equiv="cleartype" content="on">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>
		body{
			margin:0;
			background-color:white;
			height:100%;
		}

#outer {
	position: relative;
	top : 0;
	left : 0;
	width : 320px;
	height : 400px;
	overflow: hidden;
}
#menu {
	position: absolute;
	top : 0;
	left : 0;
	width : 320px;
	height : 400px;
	background-image: url('images/pw_maze_black.png');
	background-image : url('images/sidebar/sidebar_bg.png');

	background-repeat: repeat;
}
#menu * {
	font-family: Arial;
	color: white;
}
#menu ul {
	list-style-type:none;
	padding : 0;
}
#menu li {
	padding-top:12px;
	padding-bottom:12px;
	xheight:48px;
	list-style-type:none;
	border-top:1px solid #676767;
	border-bottom:1px solid #000000;
	background-image: url('images/sidebar/sidebar_bg.png');
	background-repeat: repeat;
	xpadding : 4px;
}
#menu li a {
	text-decoration: none;
	text-shadow: rgb(0, 0, 0) 3px 3px 10px;
	text-shadow: rgb(0, 0, 0) 2px 2px 3px;
	padding-top:4px;
}
#content {
	position: absolute;
	top : 0;
	left : 0;
	width : 320px;
	height : 400px;

}
#_iframe {
	position: absolute;
	top : 0;
	left : 0;
	width : 320px;
	height : 400px;

}

h1 {
    font-size: 40px;
    margin:0;
    padding:0;
    background: url(http://www.geeks3d.com/public/jegx/200812/game-texture-02.jpg) repeat 0 0, white;
    -webkit-background-clip:  text;
    -webkit-text-fill-color: transparent;
}
.page {
	padding-left:40px !important;
}
</style>
<script>
function loading() {
	var C = $("#_iframe");
	C.css('opacity',0.5);
	
}
function menu_close() {
	var C = $("#content");
	C.removeClass("x_open").animate({
		left : 0,
		duration : 200
	});
}
function menu_open() {
	var C = $("#content");
	C.addClass("x_open").animate({
		left : '200px',
		duration : 200
	});
}
function menu() {
	var C = $("#content");
	if (C.hasClass("x_open")) {
		menu_close.call(this);
	} else {
		menu_open.call(this);
	}
}
function navigate(obj,href){
//	console.log(obj);
//	console.log(href);
	$('#_iframe').attr('src',href);
	menu_close.call(this);
}

</script>
</head>
<body>
<div id='outer'>
<div id='menu'>
<?php echo $h; ?>
</div>
<div id='content'>
<iframe id='_iframe' frameborder="0" width=320 src='api.php?action=get-page&browser=app&id=1'></iframe>
</div>
</div>
</body>
<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script>
$(function(){
	var a = $("a");
	for (var i=0; i < a.length; i++) {
		var aa = $(a[i]);
		var href = aa.attr('href');
		if (href && href.indexOf("javascript") != 0) {
			aa.attr('x_href',aa.attr('href'));
			aa.attr('href','javascript:navigate(this,"'+href+'");');
		} else {
//			alert(href);
		}
	}

	/*	
	var a = $("img");
	for (var i=0; i < a.length; i++) {
		var aa = $(a[i]);
		var src = aa.attr('src');
		aa.css('-webkit-mask-image',"url("+src+")");
	}
	*/
});
</script>
</html>