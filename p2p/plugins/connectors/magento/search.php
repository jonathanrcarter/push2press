<?php

include_once("../include.php");
require_once("connect.php");

$f = new magento();
$f->connect("http://site1.cp1.glimworm.com/magento/api/v2_soap/?wsdl","carter01","vev851l");


$parent = $_GET['parent'];
if ($parent == "") {
	$parent = "1";
}

$type = $_GET['type'];
if ($type == "") {
	$type = "cats";
}

if ($type == "products") {
	$f->getproducts($parent);
} else {
	$f->gettree($parent);
} 

$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->url = $f->url;
$retval->f = $f;
$retval->data = $res;

$p2p = array();
$obj = new obj();
$obj->summary = new obj();
$obj->summary->lines = array();
$obj->details = new obj();
$obj->details->lines = array();

if ($type == "products") {

	$cnt = 0;
	foreach($f->catalogProductList as $item) {
		$cnt++;
		if ($cnt < 20) {
			array_push($obj->summary->lines,"h1:".$item->name);
			array_push($obj->details->lines,"h1:".$item->name);
			array_push($obj->details->lines,"p:SKU:".$item->sku);
			array_push($obj->details->lines,"p:product_id:".$item->product_id);
			array_push($obj->details->lines,"p:  ");
		}

	}
	array_push($p2p, $obj);

} else {

	foreach($f->catalogCategoryTree->children as $item) {
		array_push($obj->summary->lines,"h1:".$item->name);
		array_push($obj->details->lines,"h1:".$item->name);
		array_push($obj->details->lines,"h1:".$item->category_id);
		if ($item->children && count($item->children) > 0) {
			array_push($obj->details->lines,"pg:Sub Categories,http://m.push2press.com/kitchensink/plugins/connectors/magento/search.php?parent=".$item->category_id);
		}
		array_push($obj->details->lines,"pg:PRODUCTS,http://m.push2press.com/kitchensink/plugins/connectors/magento/search.php?type=products&parent=".$item->category_id);
	
	}
	array_push($p2p, $obj);
}

$retval->p2p = $p2p;


echo json_encode($retval, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);


?>