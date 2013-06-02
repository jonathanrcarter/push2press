<?php
include_once("include.php");

$endpoint = "http://dbpedia.org/sparql/";
$dsn="http://dbpedia.org/resource/DBpedia";
$query = "select distinct ?Concept where {[] a ?Concept} LIMIT 100";

$data=sparqlQuery($query, $endpoint);
$arr = array();
foreach ($data->results->bindings as $item) {
	array_push($arr, $item->Concept->value);
}
$data->items = $arr;

echo json_encode($data);

?>
