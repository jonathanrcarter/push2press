<?php

session_start();
error_reporting(0);

if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}
class obj {
}

function urlpath() {
	$retval = "http://".$_SERVER['HTTP_HOST'];
	
	$parts = explode("/",$_SERVER['SCRIPT_NAME']);
	for ($i=0; $i < count($parts)-1; $i++) {
		$retval = $retval . $parts[$i] . "/";
	}
//	$retval = $retval . "/";
	return $retval;
	
}

function getMultipleParameters() {
	$query = $_SERVER['QUERY_STRING'];
	$vars = array();
	$second = array();
	foreach (explode('&', $query) as $pair) {
		list($key, $value) = explode('=', $pair);
		if('' == trim($value)){
			continue;
		}

		if (array_key_exists($key, $vars)) {
			if (!array_key_exists($key, $second))
				$second[$key][] .= $vars[$key];
				$second[$key][] = $value;
			} else {
				$vars[$key] = urldecode($value);
			}
		}
	return array_merge($vars, $second);
}

?>