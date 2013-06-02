<?php

include_once("../include.php");
require_once("./phpFlickr-3.1/phpFlickr.php");
$f = new phpFlickr("94c57f05ba55563f88348d29bdd54593");

$id = $_GET['id'];
$owner = $f->people_getInfo($id);

$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->raw = $owner;
$retval->data = $owner;
echo json_encode($retval);




?>