<?php


echo "atom";
$file = "data.xml";
$depth = array();


$testXmlFile = "wpfeed.xml";
$download = file_put_contents($testXmlFile, file_get_contents("http://www.parkshark.eu/feed/atom/"));

echo "atom1";

//	require_once("xml2json/xml2json.php");


	$xmlStringContents = file_get_contents($testXmlFile); 
//	$jsonContents = "";

	// Convert it to JSON now. 
	// xml2json simply takes a String containing XML contents as input.
//	$jsonContents = xml2json::transformXmlStringToJson($xmlStringContents);
	
	//echo $xmlStringContents;

//	var_dump($xmlStringContents);
//echo "atom2";
//	$xml = new SimpleXMLElement($xmlStringContents);
//echo "atom3";
echo "<pre>";
	var_dump($xml);
echo "</pre>";

$xml2 = simplexml_load_file($testXmlFile,'SimpleXMLElement', LIBXML_NOCDATA);
echo "<pre>";
			for ($i=0; $i < count($xml2->entry); $i++) {
				var_dump($xml2->entry[$i]->author->name);
				echo($xml2->entry[$i]->author->name);
				echo "<br>";
				var_dump($xml2->entry[$i]->title);
				echo ($xml2->entry[$i]->title);
				echo "<br>";
				var_dump($xml2->entry[$i]);
				echo "<hr>";
			}



//var_dump($xml2);
echo "</pre>";




//$xml_parser = xml_parser_create();
//xml_set_element_handler($xml_parser, "startElement", "endElement");



echo "atom end";

?>
