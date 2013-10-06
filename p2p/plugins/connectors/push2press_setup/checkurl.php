<?php

include_once("../include.php");
include_once("simple_html_dom.php");


$val = $_GET['val'];

function check_url_format($url) {

	$pattern = '/^(?:[;\/?:@&=+$,]|(?:[^\W_]|[-_.!~*\()\[\] ])|(?:%[\da-fA-F]{2}))*$/';
	if( preg_match( $pattern, $url ) == 1 ) {
	   // url is valid
	   return true;
	}
	return false;
}




if (check_url_format($val) == true) {
	$html = file_get_html($val);
	
	
	
	if ($html) {
	
		$tags = array();
		$subpages = array();
		$images = array();
		$twitters = array();
		$facebooks = array();
		$youtubes = array();
		$mailtos = array();
		$rss = array();
		$meta = array();
		
		foreach ($html->find("a") as $col) {
			array_push($tags,$col->href);
		}

		foreach ($html->find("img") as $col) {
			array_push($images,$col->src);
		}

		foreach ($html->find("meta") as $col) {
			array_push($meta,$col->plaintext);
//			$meta = $meta . $col->plaintext . " , ";
		}
		
		foreach ($html->find("link") as $col) {
			array_push($meta,$col->rel."|".$col->type."|".$col->title."|".$col->href);
			array_push($meta,array(
				rel=>$col->rel,
				type=>$col->type,
				title=>$col->title,
				href=>$col->href)
			);
			if ($col->rel === "alternate" && $col->type === "application/rss+xml") {
				array_push($rss,$col->href);
			}
		}
		
		
		foreach ($tags as $tag) {
			if (strpos($tag,$val) === 0 && ($tag != $val) && ($tag != ($val."/")) ) {
				array_push($subpages,$tag);
			}
		}
		$subpages = array_unique($subpages);

		foreach ($subpages as $tag) {
			$html = file_get_html($tag);
			if ($html) {
				foreach ($html->find("a") as $col) {
					array_push($tags,$col->href);
				}

				foreach ($html->find("img") as $col) {
					array_push($images,$col->src);
				}
				foreach ($html->find("a") as $col) {
					array_push($tags,$col->href);
				}

			}
		}
		
		for ($i=0; $i < count($images); $i++) {
			if (strpos($images[$i],"/") === 0) {
				$images[$i] = $val.$images[$i];
			}
		}

		foreach ($tags as $tag) {
			if (strpos($tag,"http://twitter.com") === 0) array_push($twitters,$tag);
			if (strpos($tag,"https://twitter.com") === 0) array_push($twitters,$tag);
			if (strpos($tag,"http://www.twitter.com") === 0) array_push($twitters,$tag);
			if (strpos($tag,"https://www.twitter.com") === 0) array_push($twitters,$tag);

			if (strpos($tag,"http://facebook.com") === 0) array_push($facebooks,$tag);
			if (strpos($tag,"https://facebook.com") === 0) array_push($facebooks,$tag);
			if (strpos($tag,"http://www.facebook.com") === 0) array_push($facebooks,$tag);
			if (strpos($tag,"https://www.facebook.com") === 0) array_push($facebooks,$tag);

			if (strpos($tag,"http://youtube.com") === 0) array_push($youtubes,$tag);
			if (strpos($tag,"https://youtube.com") === 0) array_push($youtubes,$tag);
			if (strpos($tag,"http://www.youtube.com") === 0) array_push($youtubes,$tag);
			if (strpos($tag,"https://www.youtube.com") === 0) array_push($youtubes,$tag);

			if (strpos($tag,"mailto:") === 0) array_push($mailtos,$tag);
		}
		
		$images = array_unique($images);
		$tags = array_unique($tags);
		$meta = array_unique($meta);
		$facebooks = array_unique($facebooks);
		$twitters = array_unique($twitters);
		$youtubes = array_unique($youtubes);
		$rss = array_unique($rss);
		$mailtos = array_unique($mailtos);

		preg_match("|https?://(www\.)?twitter\.com/(#!/)?@?([^/]*)|", $html, $matches);


		$retval = new obj();
		$retval->status = 0;
		$retval->statusMsg = sprintf("valid");
		$retval->inputVal = $val;
		$retval->meta = $meta;
		$retval->subpages = $subpages;
		$retval->facebooks = $facebooks;
		$retval->twitters = $twitters;
		$retval->youtubes = $youtubes;
		$retval->rss = $rss;
		$retval->mailtos = $mailtos;
		$retval->matches = $matches;
		$retval->images = $images;
		$retval->tags = $tags;
		$retval->html = $html->plaintext;
		echo json_encode($retval);
		exit;
	}
}



$retval = new obj();
$retval->status = 1;
$retval->statusMsg = sprintf("incorrect format [%s]",$val);
echo json_encode($retval);
exit;

?>