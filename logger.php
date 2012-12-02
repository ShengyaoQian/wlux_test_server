<?php
include 'config_files.php';
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
$file = fopen($sessionDataFile,"r");
while(! feof($file)) {
    $line = fgets($file);
    $line = explode(' ', $line);
    if ($line[0] == $session) {
        $condition = $line[1];
    }
}
fclose($file);

if ($condition != -1 && !empty($session) && !empty($json)) {
    $data = "log_entry_time:\t".date('c')."\n";
    while (list($key, $value) = each($json)) {
        $data = $data . "\t". $key . ":\t" . $value . "\n";
    }
    $data = $data . "\n";

    $file = $sessionLogFolder . "session" . $session . ".txt";
    $fileResult = file_put_contents($file, $data, FILE_APPEND);
}

// otherwise we got an invalid request - just don't write anything to the file

?>

