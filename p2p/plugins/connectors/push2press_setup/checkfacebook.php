<?php

include_once("../include.php");


$val = $_GET['name'];

function check_fb_name_format($name) {
	if (!$name || $name = "") return false;
	if (strpos($name," ")) return false;
	return true;
}
function check_url_format($url) {

	$pattern = '/^(?:[;\/?:@&=+$,]|(?:[^\W_]|[-_.!~*\()\[\] ])|(?:%[\da-fA-F]{2}))*$/';
	if( preg_match( $pattern, $url ) == 1 ) {
	   // url is valid
	   return true;
	}
	return false;
}

function get($url) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL,  $url);
	curl_setopt( $ch, CURLOPT_POST, false );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	curl_close($ch);

	return array(
		"url" => $url,
		"json" => json_decode($result),
		"raw" => $result
	);
}


if (check_fb_name_format($val)) {

	$url = "https://graph.facebook.com/".$val."";
	
	
//http://graph.facebook.com/jonathanrcarter/picture?width=135&height=135

	
	
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL,  $url);
	curl_setopt( $ch, CURLOPT_POST, false );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	curl_close($ch);
	
	$autodetect = json_decode($result);
	

	$retval = new obj();
	$retval->status = 0;
	$retval->statusMsg = sprintf("valid");
	$retval->autodetect = $autodetect;
	$retval->url = $url;
	$retval->raw = $result;
	
	if (!$autodetect->error) {
		$retval->picture = get($url."/picture?redirect=false");
	}
	
	echo json_encode($retval);
	exit;
} else {
	$retval = new obj();
	$retval->status = 1;
	$retval->statusMsg = sprintf("incorrect format [%s]",$val);
	echo json_encode($retval);
	exit;
}

?>