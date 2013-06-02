<?php

include_once("../include.php");
require_once("connect.php");


function add_datavalue($arr, $dv) {

	$LNK = "http://m.push2press.com/kitchensink/plugins/connectors/wikidata/getpage.php?q";

	if ($dv["type"] == "wikibase-entityid") {
		array_push($arr,"pg:(LINK TO ENTITY ID ".$dv["value"]["numeric-id"]."),".$LNK."=q".$dv["value"]["numeric-id"]);
	} else if ($dv["type"] == "string") {
		array_push($arr,"p:STRING VALUE : ".$dv["value"]);
	}
	return $arr;
}

$f = new wikidata("");

$q = $_GET['q'];
if ($q == "") $q = "Q35127";
$res = $f->getpage($q);


$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->data = $res;
$retval->url = $f->url;

$arr = array();
$arrsummary = array();
foreach($res["entities"] as $key=>$value) {
	array_push($arrsummary,"h1:".$key);
	array_push($arrsummary,"p:pageid: ".$value["pageid"]);
	array_push($arrsummary,"p:title: ".$value["title"]);


	array_push($arr,"h1:ENTITY : ".$key);
	array_push($arr,"p:pageid: ".$value["pageid"]);
	array_push($arr,"p:ns: ".$value["ns"]);
	array_push($arr,"p:title: ".$value["title"]);
	array_push($arr,"p:lastrevid: ".$value["lastrevid"]);
	array_push($arr,"p:id: ".$value["id"]);
	array_push($arr,"p:type: ".$value["type"]);


	$LNK = "http://m.push2press.com/kitchensink/plugins/connectors/wikidata/getpage.php?q";



	array_push($arr,"h1:\n\nLABELS");
	foreach($value["labels"] as $k=>$v) {
//		array_push($arr,"h1:".$k);
		array_push($arr,"p: ".$v["language"]. ": " . $v["value"]);
	}

	array_push($arr,"h1:\n\nDESCRIPTIONS");
	foreach($value["labels"] as $k=>$v) {
//		array_push($arr,"h1:".$k);
		array_push($arr,"p: ".$v["language"]. ": " . $v["value"]);
	}

	array_push($arr,"h1:\n\nALIASES");
	foreach($value["aliases"] as $k=>$v) {
//		array_push($arr,"h1:".$k);
		foreach ($v as $kv) {
			array_push($arr,"p: ".$kv["language"]. ": " . $kv["value"]);
		}
	}

//	array_push($arr,"h1:\n\nCLAIMS");
	array_push($arr,"tab:1");
	foreach($value["claims"] as $k=>$v) {
		array_push($arr,"h1:\n\nCLAIM ".$k);
		array_push($arr,"tab:1");

		array_push($arr,"pg: >> What is ".$k." ?,".$LNK."=".$k);
		foreach ($v as $kv) {
			array_push($arr,"s:id: ".$kv["id"]);
			array_push($arr,"p:type: ".$kv["type"]);
			array_push($arr,"p:rank: ".$kv["rank"]);
			if ($kv["mainsnak"]) {
				
				array_push($arr,"h1:claim-mainsnak");
				$arr = add_datavalue($arr,$kv["mainsnak"]["datavalue"]);
			}
			if ($kv["references"]) {
				array_push($arr,"h1:claim-references");
				foreach ($kv["references"] as $ref) {
					foreach ($ref["snaks"] as $snack_k => $snack_v) {
						foreach ($snack_v as $snack_v_item) {
							$arr = add_datavalue($arr,$snack_v_item["datavalue"]);
						}
					}
				
				}
			}
		}
		array_push($arr,"btab:1");
	}
	array_push($arr,"btab:1");





	array_push($arr,"h1:\n\nSITELINKS");
	foreach($value["sitelinks"] as $k=>$v) {
		array_push($arr,"h1:".$k);
		array_push($arr,"p: - " . $v["title"]);
	}


}
$retval->p2p = array(
	array(
		"summary" => array(
			"lines" => $arrsummary
		),
		"details" => array(
			"lines" => $arr
		)
	)
);



echo json_encode($retval, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);


?>