<?php

include_once("../include.php");

$GET = getMultipleParameters();

require_once("./phpFlickr-3.1/phpFlickr.php");

$f = new phpFlickr("94c57f05ba55563f88348d29bdd54593");

$search_array = array("text"=>"amsterdam");

if ($GET['q']) {
	$search_array = array("text"=>$GET['q']);
}

$results = $f->photos_search($search_array);

$items = array();

foreach ($results['photo'] as $photo) {

	$item = new obj();
	
    $item->authorid = $photo['owner'];
    $item->author_link = "http://www.flickr.com/photos/" . $photo['owner'] . "/" . $photo['id'];
    $item->title = $photo['title'];
    $item->autor_stream = "http://www.flickr.com/people/" . $photo['owner'];
    $item->thumbnail = sprintf("http://farm%s.staticflickr.com/%s/%s_%s.jpg'",$photo['farm'], $photo['server'], $photo['id'], $photo['secret']);
    $item->getperson = urlpath() ."getperson.php?id=".$photo['owner'];
    $item->getpicture = urlpath() ."getpicture.php?id=".$photo['id'];
	array_push($items,$item);

}    



$retval = new obj();
$retval->status = 0;
$retval->statusMsg = sprintf("success");
$retval->raw = $recent;
$retval->data = $items;
echo json_encode($retval);




?>