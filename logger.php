<?php

// Logs page transitions on a weblabux study site.

// In the final implementation, this will obviously write data to
// a database instead of a text file.

$type = $_POST["type"];  // the type of action we're logging

$data_arr = array();

if ($type == "open") {
    $data_arr = array("OPEN",
                      "\tSESSION:\t" . $_POST["wlux_session"],
                      "\tCONDITION:\t" . $_POST["wlux_condition"],
                      "\tLOCATION:\t" . $_POST["location"],
                      "\tTIME:\t" . date('m/d/Y h:i:s a', time()));
} else if ($type == "transition") {
    $data_arr = array("TRANSITION",
                      "\tSESSION:\t" . $_POST["wlux_session"],
                      "\tCONDITION:\t" . $_POST["wlux_condition"],
                      "\tFROM:\t" . $_POST["from"],
                      "\tTO:\t\t" . $_POST["to"],
                      "\tTIME:\t" . date('m/d/Y h:i:s a', time()));

}

$file = "sess" . $_POST["wlux_session"] . "cond" . $_POST["wlux_condition"] . ".txt";
$data = implode("\n", $data_arr) . "\n\n";
$fileResult = file_put_contents('study_results/' . $file, $data, FILE_APPEND);

/*
if ($fileResult === false) {
	// error logging data
	http_response_code(500);
} else {
	// success
	http_response_code(200);
}
*/
?>

