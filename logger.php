<?php

// Logs page transitions on a weblabux study site.

// In the final implementation, this will obviously write data to
// a database instead of a text file.

$type = $_POST["type"];  // the type of action we're logging
$data_arr = array();
$condition = -1;
$session = $_POST["wlux_session"];

// get the condition from the sessions.txt (eventually get it from a db given
// the session id)
$file = fopen("sessions.txt","r");
while(! feof($file)) {
    $line = fgets($file);
    $line = explode(' ', $line);
    if ($line[0] == $session) {
        $condition = $line[1];
    }
}
fclose($file);

if ($condition != -1 && !isEmpty($session)) {
    if ($type == "open") {
        $data_arr = array("OPEN",
                          "\tSESSION:\t" . $_POST["wlux_session"],
                          "\tCONDITION:\t" . $condition,
                          "\tLOCATION:\t" . $_POST["location"],
                          "\tTIME:\t" . date('m/d/Y h:i:s a', time()));
    } else if ($type == "transition") {
        $data_arr = array("TRANSITION",
                          "\tSESSION:\t" . $_POST["wlux_session"],
                          "\tCONDITION:\t" . $condition,
                          "\tFROM:\t" . $_POST["from"],
                          "\tTO:\t\t" . $_POST["to"],
                          "\tTIME:\t" . date('m/d/Y h:i:s a', time()));

    }

    $file = "sess" . $_POST["wlux_session"] . "cond" . $_POST["wlux_condition"] . ".txt";
    $data = implode("\n", $data_arr) . "\n\n";
    $fileResult = file_put_contents('study_results/' . $file, $data, FILE_APPEND);
}

// otherwise we got an invalid request - just don't write anything to the file

?>

