<?php


echo dirname(__FILE__);

echo "downloading version from github to ". dirname(__FILE__);

$download = file_put_contents("upgrade.zip", file_get_contents("https://github.com/jonathanrcarter/push2press/archive/master.zip"));

if ($download == false) {
	echo "exiting, error downloading code";
	exit;
}

$download = file_put_contents("pclzip.lib.php", file_get_contents("https://raw.github.com/jonathanrcarter/push2press/master/p2p/pclzip-2-8-2/pclzip.lib.php"));

if ($download == false) {
	echo "exiting, error downloading code";
	exit;
}


require_once('pclzip.lib.php');
$archive = new PclZip('upgrade.zip');

//$list = $archive->listContent();
//var_dump($list);

if ($archive->extract(PCLZIP_OPT_REMOVE_PATH, "push2press-master/p2p",PCLZIP_OPT_REPLACE_NEWER) == 0) {
  echo 'doh!';
} else {
  echo 'AAAAH!';
}


echo "<div><a href='api.php'>You can proceed to your site</a></div>";



?>