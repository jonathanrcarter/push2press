<?php

include_once("../include.php");
require_once("connect.php");

$f = new sparql("");

$srch = $_GET['srch'];

$query = '
PREFIX ah: <http://purl.org/artsholland/1.0/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX dc: <http://purl.org/dc/terms/>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX time: <http://www.w3.org/2006/time#>
PREFIX geo: <http://www.w3.org/2003/01/geo/wgs84_pos#>
PREFIX vcard: <http://www.w3.org/2006/vcard/ns#>
PREFIX osgeo: <http://rdf.opensahara.com/type/geo/>
PREFIX bd: <http://www.bigdata.com/rdf/search#>
PREFIX search: <http://rdf.opensahara.com/search#>
PREFIX fn: <http://www.w3.org/2005/xpath-functions#>
PREFIX gr: <http://purl.org/goodrelations/v1#>
PREFIX gn: <http://www.geonames.org/ontology#>
SELECT ?venue_title ?venue ?event ?date ?production ?production_title
WHERE {
	?venue a ah:Venue ;
		dc:title ?venue_title ;
		ah:locationAddress ?address .	

	?address vcard:locality "Amsterdam" .
	
	?event a ah:Event ;
		ah:venue ?venue ;
		ah:production ?production ;
		time:hasBeginning ?date .

	?production dc:title ?production_title ;

	FILTER (?date >= "2013-06-12"^^xsd:dateTime)
	FILTER (?date < "2013-06-19"^^xsd:dateTime)
	
	FILTER (langMatches(lang(?venue_title), "nl"))   
	FILTER (langMatches(lang(?production_title), "nl"))   
} ORDER BY ?date LIMIT 100';


$res = $f->search($query);

$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->data = $res;
$retval->url = $f->url;
echo json_encode($retval, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);


?>