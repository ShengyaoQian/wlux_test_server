<?php
    // set the returnURL dynamically depending on whether we're on the
    // development or produciton environment.
    // NOTE: this assumes that the site and server directories are in the root
    // of the site only when developing on localhost
    $LOCAL = is_dir("../site");
    $returnURL = "http://staff.washington.edu/rbwatson/end.php";
    if ($LOCAL) {
        $returnURL = "/server/end.php";
    }

    $data = array();
    $condition = "";
    $cssURL = "";
    $session = $_GET["wlux_session"];

    // get the data from the file. Eventually we need to query a database and
    // ensure the session is a valid, open session
    $file = fopen("sessions.txt","r");
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
                      "buttonText" => "End Study",
                      "returnURL" => $returnURL,
                      "taskText" => "Learn to play hearts and spades.");
    } else {
        // this will trigger the jquery ajax call's error handling callback
        header("HTTP/1.1 404 Not Found");
    }

    $jsonpTag = $_GET["callback"]; // set by jquery ajax call when using jsonp data type
    if (!empty($jsonpTag)) {
        header('content-type: application/json');
        echo $jsonpTag . '(' . json_encode($data) . ')';
    } else {
		// this line only works on PHP > 5.4.0, which not everyone seems to have.
        //   http_response_code(500);
		// this works on PHP > 4.3 (or so)
		if (!headers_sent()) {
			header('X-PHP-Response-Code: 500', true, 500);
		}
		// else too late to send a header with an error code
	}
?>
