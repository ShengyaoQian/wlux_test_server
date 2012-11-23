<?php
    // this assumes that the site and server directories are in the root
    // of the site only when developing on localhost
    $DEBUG = is_dir("../site");
    $returnURL = "http://staff.washington.edu/rbwatson/study_data.php";
    $file = fopen("sessions.txt","r");

    $data = array();
    $condition = "";
    $cssURL = "";
    while(! feof($file)) {
        $line = fgets($file);
        $line = explode(' ', $line);
        if ($line[0] == $_POST["wlux_session"]) {
            $condition = $line[1];
            $cssURL = trim($line[2]);
        }
    }
    fclose($file);

    if ($DEBUG) {
        $returnURL = "/server/end.php";
    }

    if ($condition != "" && $cssURL != "") {
        $data = array("conditionId" => $condition,
                      "cssURL" => $cssURL,
                      "buttonText" => "End Study",
                      "returnURL" => $returnURL);
    }


    header('content-type: application/json');
    echo json_encode($data);
?>
