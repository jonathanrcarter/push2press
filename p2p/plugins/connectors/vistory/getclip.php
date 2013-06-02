<?php

include_once("../include.php");
require_once("vistory.php");

echo "<pre>";

$f = new vistory_connect("");

$id = $_GET['id'];
$res = $f->getclip($id);

$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->data = $res;
echo json_encode($retval);


?>