<?php
include_once("include.php");

$endpoint = "http://bnb.data.bl.uk/sparql";
$dsn="http://api.talis.com/stores/bbc-wildlife";
$dsn = "";

$query = "select distinct ?Concept where {[] a ?Concept} LIMIT 100";

$data=sparqlQuery($query, $endpoint);

echo json_encode($data);

?>
