<?php

include_once("../include.php");
require_once("connect.php");

$f = new tagme("456380gjdo");
$q = $_GET['q'];
$f->search($q);

$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->url = $f->url;
$retval->f = $f;
$retval->data = $res;

echo json_encode($retval, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);


?>