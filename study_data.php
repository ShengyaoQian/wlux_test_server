<?php
    // this assumes that the site and server directories are in the root
    // of the site only when developing on localhost
	$success = true;
    $DEBUG = is_dir("../site");
    $returnURL = "http://staff.washington.edu/rbwatson/end.php";
    $file = fopen("sessions.txt","r");
	if (false === $file) {$success = false;}
	// normally this page is called by a POST command, but GET is
	// also supported for testing from a browser window. The GET 
	// variables could be removed in production, if they pose a problem
	if ($success) {
		$sessionToFind = (empty($_POST["wlux_session"]) ? $_GET['wlux_session'] : $_POST["wlux_session"]);
		$jsonpTag = (empty($_POST["tag"]) ? $_GET['tag'] : $_POST["tag"]);
		$data = array();
		$condition = "";
		$cssURL = "";
		
		while(! feof($file)) {
			$line = fgets($file);
			$line = explode(' ', $line);
			if ($line[0] == $sessionToFind) {
				$condition = $line[1];
				$cssURL = trim($line[2]);
			}
		}
		fclose($file);
	}

    if ($DEBUG) {
        $returnURL = "/server/end.php";
    }

    if ($condition != "" && $cssURL != "" && $success === true) {
        $data = array("conditionId" => $condition,
                      "cssURL" => $cssURL,
                      "buttonText" => "End Study",
                      "returnURL" => $returnURL);
		//http_response_code(200);
    } else {
		// error return case
		$data = array("conditionId" => '0',
		              "error" => "Unable to find session",
                      "buttonText" => "End Study",
                      "returnURL" => $returnURL);
  		//http_response_code(500);

	}

    header('content-type: application/json');
	if (!empty($jsonpTag)) {
		echo $jsonpTag.'(';
	}
	echo json_encode($data);
	if (!empty($jsonpTag)) {
		echo ');';
	}
?>
