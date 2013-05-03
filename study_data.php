<?php
include 'config_files.php';
    // set the returnURL dynamically depending on whether we're on the
    // development or produciton environment.
    // NOTE: this assumes that the site and server directories are in the root
    // of the site only when developing on localhost

    $data = array();
    $session = $_GET["wlux_session"];



    $path = "../config_file/";
    $file_name = "config.txt";
    if (file_exists($path.$file_name)) {
        $data = unserialize(file_get_contents($path.$file_name));
        if ($data["conditionId"] == "" || $data["cssURL"] == "") {
            // this will trigger the jquery ajax call's error handling callback
            header("HTTP/1.1 404 Not Found");
        }
    } else {

        $LOCAL = is_dir("../wlux_test_site");
        $serverRoot = "http://staff.washington.edu/rbwatson/";
        if ($LOCAL) {
            $serverRoot = "/server/";
        }
        $returnURL = $serverRoot."end.php";
        $taskBarCSS = $serverRoot."wluxTaskBar.css";

        $condition = "";
        $cssURL = "";

        // get the data from the file. Eventually we need to query a database and
        // ensure the session is a valid, open session
        $file = fopen($sessionDataFile,"r");
        while(! feof($file)) {
            $line = fgets($file);
            $line = explode(' ', $line);
            if ($line[0] == $session) {
                $condition = $line[1];
                $cssURL = trim($line[2]);
            }
        }
        fclose($file);

        if ($condition != "" && $cssURL != "") {
            $data = array("conditionId" => $condition,
                          "cssURL" => $cssURL,
                          "taskBarCSS" => $taskBarCSS,
                          "buttonText" => "End study",
                          "returnURL" => $returnURL,
                          // taskHTML will override taskText if both are sent
                          // but one or the other must be present 
                          "taskText" => "Task: Learn to play hearts and spades to become a card shark.",
                          "taskHTML" => "<p class='wlux_taskbar_text'><strong>Task 1:</strong> This is some <i>formatted</i> text</p>",
                          "tabShowText" => "Show",
                          "tabHideText" => "Hide",
                          );
        } else {
            // this will trigger the jquery ajax call's error handling callback
            header("HTTP/1.1 404 Not Found");
        }

    }


    $jsonpTag = $_GET["callback"]; // set by jquery ajax call when using jsonp data type
    if (!empty($jsonpTag)) {
        header('content-type: application/json');
        echo $jsonpTag . '(' . json_encode($data) . ')';
    } else {
        // this line only works on PHP > 5.4.0, which not everyone seems to have.
        // http_response_code(500);
        // this works on PHP > 4.3 (or so)
        if (!headers_sent()) {
            header('X-PHP-Response-Code: 500', true, 500);
        }
        // else too late to send a header with an error code
    }
?>
