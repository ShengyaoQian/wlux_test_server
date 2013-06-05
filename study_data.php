<?php
	include 'config_files.php';
    // set the returnURL dynamically depending on whether we're on the
    // development or produciton environment.
    // NOTE: this assumes that the site and server directories are in the root
    // of the site only when developing on localhost

    $data = array();
    $session = $_GET["wlux_session"];

	$LOCAL = is_dir("../wlux_test_site");
	$serverRoot = "http://staff.washington.edu/rbwatson/";
	if ($LOCAL) {
		$serverRoot = "/server/";
	}
	     
    if (file_exists($CONFIG_FILE_PATH.$CONFIG_FILE_NAME)) {
        // Create a connection to the database.
        $database = 'db_test';
        $hostname = 'wlux.uw.edu';
        $username = 'db_test';
        $password = 'WeCantDecide2';
        $db = new PDO("mysql:dbname=$database;host=$hostname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // get all the rows from the tables
        $sessionConfig = $db->query("SELECT * FROM session_config");
        $transactionLog = $db->query("SELECT * FROM transaction_log");


        // Things need to be fixed below
        // Don't know which part in the table I can find the cssURL
        if ( $transactionLog["conditionId"] == "" || $data["cssURL"] == "") {
			// file parsing error
            // this will trigger the jquery ajax call's error handling callback
            header("HTTP/1.1 404 Not Found");
        } else {
			// wrap data in jsonp format
			$jsonpTag = $_GET["callback"]; // set by jquery ajax call when using jsonp data type
			if (!empty($jsonpTag)) { 
				// format and send output
				header('content-type: application/json');
				echo $jsonpTag . '(' . json_encode($sessionConfig) . json_encode($transactionLog) . ')';
			} else {
				// no callback param name so return an error
				// this line only works on PHP > 5.4.0, which not everyone seems to have.
				//   http_response_code(500);
				// this works on PHP > 4.3 (or so)
				if (!headers_sent()) {
					header('X-PHP-Response-Code: 500', true, 500);
				}
			} 
		}
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
