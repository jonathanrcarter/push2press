<?php

include_once("../include.php");
require_once("connect.php");

$f = new artsholland("9cbce178ed121b61a0797500d62cd440");

$srch = $_GET['srch'];

$res = $f->search($srch);

$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->data = $res;

$p2p = array();
foreach($res["results"] as $item) {
	$obj = new obj();
	$obj->summary = new obj();
	$obj->summary->lines = array();
	$obj->details = new obj();
	$obj->details->lines = array();
	
	array_push($obj->summary->lines,"h1:".$item["title"]);
	array_push($obj->summary->lines,"p:".strip_tags($item["shortDescription"]));
	array_push($obj->details->lines,"h1:".$item["title"]);
	array_push($obj->details->lines,"p:".strip_tags($item["description"]));
	array_push($obj->details->lines,"h1:Opening hours");
	array_push($obj->details->lines,"p:".strip_tags($item["openingHours"]));
	array_push($obj->details->lines,"h1:Getting there");
	array_push($obj->details->lines,"p:".strip_tags($item["publicTransportInformation"]));
	array_push($obj->details->lines,"h1:Email");
	array_push($obj->details->lines,"p:".$item["email"]);
	if ($item["disabilityInformation"]) {
		array_push($obj->details->lines,"h1:disability Information");
		array_push($obj->details->lines,"p:".strip_tags($item["disabilityInformation"]));
	}
	array_push($obj->details->lines,"h1:Telephone");
	array_push($obj->details->lines,"p:".$item["telephone"]);
	array_push($p2p, $obj);

}

$retval->p2p = $p2p;


echo json_encode($retval, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);


?>