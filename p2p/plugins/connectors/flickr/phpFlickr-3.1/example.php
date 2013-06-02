<?php
/* Last updated with phpFlickr 1.3.2
 *
 * This example file shows you how to call the 100 most recent public
 * photos.  It parses through them and prints out a link to each of them
 * along with the owner's name.
 *
 * Most of the processing time in this file comes from the 100 calls to
 * flickr.people.getInfo.  Enabling caching will help a whole lot with
 * this as there are many people who post multiple photos at once.
 *
 * Obviously, you'll want to replace the "<api key>" with one provided 
 * by Flickr: http://www.flickr.com/services/api/key.gne
 */

require_once("phpFlickr.php");
$f = new phpFlickr("94c57f05ba55563f88348d29bdd54593");

//$recent = $f->photos_getRecent();
$recent = $f->photos_search(array("text"=>"family history","sort"=>"relevant"));


/*echo "<pre>";
var_dump($recent);
echo "</pre>";
*/

//foreach ($recent['photos']['photo'] as $photo) {
foreach ($recent['photo'] as $photo) {
    $owner = $f->people_getInfo($photo['owner']);
    echo "<a href='http://www.flickr.com/photos/" . $photo['owner'] . "/" . $photo['id'] . "/'>";
    echo $photo['title'];
    echo "</a> Owner: ";
    echo "<a href='http://www.flickr.com/people/" . $photo['owner'] . "/'>";
    echo $owner['username'];
    echo "</a><br>";
    echo sprintf("<img src='http://farm%s.staticflickr.com/%s/%s_%s.jpg'",$photo['farm'], $photo['server'], $photo['id'], $photo['secret']);
    echo "<br>";

  /*  


      array(9) {
    ["id"]=>
    string(10) "8796098413"
    ["owner"]=>
    string(12) "20056291@N00"
    ["secret"]=>
    string(10) "41d5be5f62"
    ["server"]=>
    string(4) "3735"
    ["farm"]=>
    float(4)
    ["title"]=>
    string(5) "SÃ¨te"
    ["ispublic"]=>
    int(1)
    ["isfriend"]=>
    int(0)
    ["isfamily"]=>
    int(0)
  }
<img src="http://farm6.staticflickr.com/5180/5574327083_8bf11b254a.jpg" width="436" height="293" alt="CLIFF COTTAGE.  THE LOWER WEST CLIFF.  BOURNEMOUTH.  1863" class="pc_img " border="0">    
  */
    
}

?>
