<?php

include_once("../include.php");
include_once("simple_html_dom.php");

$srch = $_GET['srch'];
if ($srch == "") {
	$srch = 'http://www.compendiumvoordeleefomgeving.nl/indicatoren/nl0105-Mestproductie-per-landbouwgebied.html?i=3-17&source=rss';
}

$html = file_get_html($srch);
$res = new obj();


$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->data = $res;

$obj = new obj();
$obj->summary = new obj();
$obj->summary->lines = array();
$obj->details = new obj();
$obj->details->lines = array();

$arr = array();
$arrsummary = array();

foreach($html->find("#leftColumn") as $col) {

	foreach($col->find('h1') as $element) {
		array_push($arr,"h1:".$element->plaintext);
	}

	foreach($col->find('.lead') as $element) {
		array_push($arr,"p:".$element->plaintext);
	}

	foreach($col->find('.figuur') as $element) {
		if ($element->src != "") {
//			array_push($arr,"img320:http://www.compendiumvoordeleefomgeving.nl/indicatoren/".$element->src);
			array_push($arr,"zimg320:http://www.compendiumvoordeleefomgeving.nl/indicatoren/".$element->src.",http://www.compendiumvoordeleefomgeving.nl/indicatoren/".$element->src);
		}
	}
	
	foreach($col->find('.paragraafkop') as $element) {
		array_push($arr,"p:".$element->plaintext);
	}

	array_push($arr,"p:".$col->plaintext);
	

}


//array_push($p2p, $obj);
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