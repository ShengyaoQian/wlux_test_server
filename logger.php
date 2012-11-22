<?php

// Logs page transitions on a weblabux study site.

// In the final implementation, this will obviously write data to
// a database instead of a text file.
$postCall = 1;
$type = $_POST["type"];  // the type of action we're logging
if (empty($type)){
	$type = $_GET["type"];
	$postCall = 0;
}

$data_arr = array();

if (empty($type)){
	$data_arr = array("ERROR",	
					  "\tTIME:\t" . date('m/d/Y h:i:s a', time()));
} else {
	if ($type == "open") {
		$data_arr = array("OPEN",
						  "\tSESSION:\t" . (($postCall == 1) ? $_POST["wlux_session"] : $_GET["wlux_session"]),
						  "\tLOCATION:\t" . (($postCall == 1) ? $_POST["location"] : $_GET["location"]),
						  "\tTIME:\t" . date('m/d/Y h:i:s a', time()));
	} else if ($type == "transition") {
		$data_arr = array("TRANSITION",
						  "\tSESSION:\t" . (($postCall == 1) ? $_POST["wlux_session"] : $_GET["wlux_session"]),
						  "\tFROM:\t" . (($postCall == 1) ? $_POST["from"] : $_GET["from"]),
						  "\tTO:\t\t" . (($postCall == 1) ? $_POST["to"] : $_GET["to"]),
						  "\tTIME:\t" . date('m/d/Y h:i:s a', time()));
	}
}

$data = implode("\n", $data_arr) . "\n\n";
file_put_contents("log.txt", $data, FILE_APPEND);

?>

