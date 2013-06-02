<?php

include_once("../include.php");

$GET = getMultipleParameters();

require_once("./phpFlickr-3.1/phpFlickr.php");

$f = new phpFlickr("94c57f05ba55563f88348d29bdd54593");

$search_array = array("text"=>"amsterdam");

if ($GET['srch']) {
	$search_array = array("text"=>$GET['srch']);
}

$results = $f->photos_search($search_array);

$items = array();
$p2p = array();

foreach ($results['photo'] as $photo) {

	$item = new obj();
	
    $item->authorid = $photo['owner'];
    $item->author_link = "http://www.flickr.com/photos/" . $photo['owner'] . "/" . $photo['id'];
    $item->title = $photo['title'];
    $item->autor_stream = "http://www.flickr.com/people/" . $photo['owner'];
    $item->thumbnail = sprintf("http://farm%s.staticflickr.com/%s/%s_%s.jpg",$photo['farm'], $photo['server'], $photo['id'], $photo['secret']);
    $item->getperson = urlpath() ."getperson.php?id=".$photo['owner'];
    $item->getpicture = urlpath() ."getpicture.php?id=".$photo['id'];
	array_push($items,$item);

	$item2 = new obj();
	$item2->summary = new obj();
	$item2->summary->lines = array(
		"img:".$item->thumbnail,
		"h1:".$item->title,
		"p:  "
	);
	
	$item2->details = new obj();
	$item2->details->lines = array(
		"img:".$item->thumbnail,
		"h1:".$item->title,
		"p:  "
	);
	
	array_push($p2p,$item2);

}    



$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->raw = $recent;
$retval->data = new obj();
$retval->data->items = $items;
$retval->data->p2p = $p2p;
echo json_encode($retval);




?>