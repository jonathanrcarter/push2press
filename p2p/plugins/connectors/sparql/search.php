<?php

include_once("../include.php");
require_once("connect.php");

$f = new victoria_and_albert("");

$srch = $_GET['srch'];
$res = $f->search($srch);

$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->data = $res;
$retval->url = $f->url;
echo json_encode($retval, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);


?>