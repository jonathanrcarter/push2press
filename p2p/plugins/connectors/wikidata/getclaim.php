<?php

include_once("../include.php");
require_once("connect.php");

$f = new wikidata("");

$q = $_GET['q'];
if ($q == "") $q = "Q35127";
$res = $f->getclaim($q);


$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->data = $res;
$retval->url = $f->url;

$arr = array();
array_push($arr,"h1:".$q);

/*

$arrsummary = array();
foreach($res["entities"] as $key=>$value) {
	array_push($arrsummary,"h1:".$key);
	array_push($arrsummary,"p:pageid: ".$value["pageid"]);
	array_push($arrsummary,"p:title: ".$value["title"]);


	array_push($arr,"h1:".$key);
	array_push($arr,"p:pageid: ".$value["pageid"]);
	array_push($arr,"p:ns: ".$value["ns"]);
	array_push($arr,"p:title: ".$value["title"]);
	array_push($arr,"p:lastrevid: ".$value["lastrevid"]);
	array_push($arr,"p:id: ".$value["id"]);
	array_push($arr,"p:type: ".$value["type"]);

	array_push($arr,"h1:\n\nCLAIMS");
	foreach($value["claims"] as $k=>$v) {
		array_push($arr,"h1:".$k);
		foreach ($v as $kv) {
			array_push($arr,"p:id: ".$kv["id"]);
			array_push($arr,"p:type: ".$kv["type"]);
			array_push($arr,"p:rank: ".$kv["rank"]);
			array_push($arr,"p:datavalue: ".$kv["datavalue"]["type"]);
			array_push($arr,"p:entity-type:\n".$kv["datavalue"]["value"]["entity-type"]);
			array_push($arr,"p:numeric-id:\n".$kv["datavalue"]["value"]["numeric-id"]);
			array_push($arr,"p: ".$kv["language"]. ":\n - " . $kv["value"]);
			array_push($arr,"pg:link to claim,http://www.glimworm.com");
			array_push($arr,"p:link to claim,http://www.glimworm.com");
		}
	}


	array_push($arr,"h1:\n\nALIASES");
	foreach($value["aliases"] as $k=>$v) {
		array_push($arr,"h1:".$k);
		foreach ($v as $kv) {
			array_push($arr,"p:: ".$kv["language"]. ":\n - " . $kv["value"]);
		}
	}
	array_push($arr,"h1:\n\nLABELS");
	foreach($value["labels"] as $k=>$v) {
		array_push($arr,"h1:".$k);
		array_push($arr,"p: ".$v["language"]. ":\n - " . $v["value"]);
	}
	array_push($arr,"h1:\n\nDESCRIPTIONS");
	foreach($value["labels"] as $k=>$v) {
		array_push($arr,"h1:".$k);
		array_push($arr,"p: ".$v["language"]. ":\n - " . $v["value"]);
	}



	array_push($arr,"h1:\n\nSITELINKS");
	foreach($value["sitelinks"] as $k=>$v) {
		array_push($arr,"h1:".$k);
		array_push($arr,"p:: ".$v["site"]. ":\n - " . $v["title"]);
	}


}

*/

$retval->p2p = array(
	"lines" => $arr
);



echo json_encode($retval, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);


?>