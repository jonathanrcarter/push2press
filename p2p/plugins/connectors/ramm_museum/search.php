<?php

include_once("../include.php");

require_once("connect.php");


$f = new ramm_museum("");
echo "<pre>";
var_dump($f);
echo "</pre>";

$srch = $_GET['srch'];

$f->login("jcglimworm","vev851l");

echo "<pre>";
var_dump($f);
echo "</pre>";

$res = $f->search($srch);

$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->data = $res;
echo json_encode($retval, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);


?>