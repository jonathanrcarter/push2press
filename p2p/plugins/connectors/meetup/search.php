<?php

include_once("../include.php");
require_once("connect.php");

$f = new meetup("162d67f467f223e68724c3266627767");

$group_urlname = $_GET['group_urlname'];
if ($group_urlname == "") {
	$group_urlname = "Appsterdam";
}

$offset = $_GET['offset'];
if ($offset == "") {
	$offset = "0";
}

$res = $f->search($group_urlname,$offset);

$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->url = $f->url;
$retval->data = $res;

$p2p = array();
foreach($res["results"] as $item) {
	$obj = new obj();
	$obj->summary = new obj();
	$obj->summary->lines = array();
	$obj->details = new obj();
	$obj->details->lines = array();
	
	$start = DateTime::createFromFormat("U",substr($item["time"],0,-3));
	$dmy = date_format(format,'Y-m-d');

	array_push($obj->summary->lines,"img:http://appsterdam.rs/wp-content/themes/appsterdam/img/logo.png");
	array_push($obj->summary->lines,"h11:".$item["name"]);
	
	array_push($obj->summary->lines,"p:".date_format($start,'Y-m-d'));
	array_push($obj->summary->lines,"p:".date_format($start,'H:i'));
	
	
	array_push($obj->details->lines,"img:http://appsterdam.rs/wp-content/themes/appsterdam/img/logo.png");
	array_push($obj->details->lines,"h11:".$item["name"]);
	array_push($obj->details->lines,"p:".date_format($start,'Y-m-d'));
	array_push($obj->details->lines,"p:".date_format($start,'H:i'));
	array_push($obj->details->lines,"h1:Description");
	array_push($obj->details->lines,"p:".strip_tags($item["description"]));
	array_push($obj->details->lines,"p:  ");
	array_push($obj->details->lines,"img:http://img2.meetupstatic.com/906521611995523788/img/header/logo.png");


	array_push($p2p, $obj);

}

$retval->p2p = $p2p;


echo json_encode($retval, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);


?>