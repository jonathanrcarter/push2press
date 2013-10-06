<?php

include_once("../include.php");
require_once("connect.php");

$f = new rss("");

$srch = $_GET['srch'];
$res = $f->search("");

$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->data = $res;

$p2p = array();
foreach($res->channel->item as $item) {
	$obj = new obj();
	$obj->summary = new obj();
	$obj->summary->lines = array();
	$obj->details = new obj();
	$obj->details->lines = array();


	array_push($obj->summary->lines,"h1:".$item->title);
	array_push($obj->summary->lines,"p:".$item->pubDate);

	array_push($obj->details->lines,"h1:".$item->title);
	array_push($obj->details->lines,"p:".$item->pubDate);
	array_push($obj->details->lines,"pg:more,".$item->link);

	$LNK = "http://m.push2press.com/kitchensink/plugins/connectors/rss_cvlo/getpage.php?pg=".$item->link;
	array_push($obj->details->lines,"pg:(LINK TO ENTITY ID ,".$LNK);
	

/*
	$start = new DateTime($item["gd\$when"][0]["startTime"]);
	$end = new DateTime($item["gd\$when"][0]["endTime"]);
	
	
	array_push($obj->summary->lines,"h1:".$item["title"]["\$t"]);
	array_push($obj->summary->lines,"p:".$start->format('Y-m-d'));
	array_push($obj->summary->lines,"p:".$start->format('H:i') ." - ".$end->format('H:i'));
	

	array_push($obj->details->lines,"h1:".$item["title"]["\$t"]);
	array_push($obj->details->lines,"p:".$start->format('Y-m-d'));
	array_push($obj->details->lines,"p:".$start->format('H:i') ." - ".$end->format('H:i'));
	array_push($obj->details->lines,"h1:Description");

	$description = $item["content"]["\$t"];
	
	if (strpos($description, "#p2p") > -1) {
		$lines = explode("\n",$description);
		$obj->details->lines = array_merge($obj->details->lines, $lines);
	} else {
		array_push($obj->details->lines,"p:".$item["content"]["\$t"]);
	}

*/


	array_push($p2p, $obj);
}

$retval->p2p = $p2p;


echo json_encode($retval, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);


?>