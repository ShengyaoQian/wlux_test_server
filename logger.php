<?php

// Logs page transitions on a weblabux study site.

// In the final implementation, this will obviously write data to
// a database instead of a text file.

$type = $_POST["type"];  // the type of action we're logging
$data_arr = array();
$condition = -1;

$json = $_POST["data"];
$session = $json["wlux_session"];

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

if ($condition != -1 && !empty($session) && !empty($json)) {
    // rbwatson - added time stamp to log entry
    $data = "log_entry_time:\t".date('c')."\n";
    // TODO: ajay- make this pretty
    while (list($key, $value) = each($json)) {
        $data = $data . "\t". $key . ":\t" . $value . "\n";
    }
    $data = $data . "\n";

    $file = "session" . $session . ".txt";
    $fileResult = file_put_contents("study_results/" . $file, $data, FILE_APPEND);
}

// otherwise we got an invalid request - just don't write anything to the file

?>

