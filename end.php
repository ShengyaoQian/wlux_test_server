<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
        p, h2 { text-align: center; margin-top: 1em; }
        </style>
    </head>
    <body>
        <?php
            // todo - add error handling
            $file = "sess" . $_GET["wlux_session"] . "cond" . $_GET["wlux_condition"] . ".txt"
        ?>
        <h2>WebLabUX Survey</h2>
        <p>Thank you for taking part in our survey!!</p>
        <p><a href="./study_results/<?=$file?>" target="_blank">View the data collected from this study in a new window</a></p>
        <p><a href="start.php">Start another study</a></p>
    </body>
</html>
