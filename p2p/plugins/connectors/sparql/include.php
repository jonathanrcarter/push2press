<?php

#
# Demonstrating use of a single query to populate a # Virtuoso Quad Store via PHP. 
#

# HTTP URL is constructed accordingly with JSON query results format in mind.

function sparqlQuery($query, $baseURL, $format="application/json")

  {
	$params=array(
		"default-graph" =>  "",
		"should-sponge" =>  "soft",
		"query" =>  $query,
		"debug" =>  "on",
		"timeout" =>  "",
		"format" =>  $format,
		"save" =>  "display",
		"fname" =>  ""
	);

	$querypart="?";	
	foreach($params as $name => $value) 
  {
		$querypart=$querypart . $name . '=' . urlencode($value) . "&";
	}
	
	$sparqlURL=$baseURL . $querypart;
	
	return json_decode(file_get_contents($sparqlURL));
};
?>