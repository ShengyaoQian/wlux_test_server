<?php
    $DEBUG = is_dir("../site");
    $studies_url = "http://staff.washington.edu/rbwatson/study_results/";
    if ($DEBUG) {
        $studies_url = "/server/study_results/";
    }

    // todo - add error handling
    $file = "sess" . $_GET["wlux_session"] . "cond" . $_GET["wlux_condition"] . ".txt";
    $study_url = $studies_url . $file;
?>
<html>
    <head>
        <style type="text/css">
        p, h2 { text-align: center; margin-top: 1em; }
        </style>
    </head>
    <body>
        <h2>WebLabUX Survey</h2>
        <p>Thank you for taking part in our survey!!</p>
        <p>Click <a href="<?=$study_url ?>">here</a> to view the data collected.</p>
    </body>
</html>
