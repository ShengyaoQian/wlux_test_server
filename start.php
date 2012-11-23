<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php
		// uncomment the next line for local testing
		// $siteRoot 1 '/site/';
		// uncomment the following line for use on the integration servers
		$siteRoot = "http://students.washington.edu/rbwatson/";
	?>
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
        h2 { margin-top: 1em; }
        div.container { width: 500px; margin: auto; }
        </style>
    </head>

    <body>
        <div class="container">

            <?php
            $filename = "sessions.txt";

            //first, obtain the data initially present in the text file
            $ini_handle = fopen($filename, "r");
            $old_string = fread($ini_handle, filesize($filename));

            //parse string
            $array = explode(" ", $old_string);
            $wlux_sid = $array[0];   //session ID passed to the study site
            $condition = $array[1];

            fclose($ini_handle);
            //done obtaining initially present data
            $handle = fopen($filename, "w");

            //new incremented session ID
            $new_sid = $wlux_sid + 1;

            //incrementing condition - 1,2,3, or 4
            $condition = $condition + 1;

            if($condition == 5)
                $condition = 1;

            //adding CSS stylesheets for the condition
            $css = 'css/style'.$condition.'.css';

            $new_string = $new_sid.' '.$condition.' '.$css."\n".$old_string;

            if (fwrite($handle, $new_string) === false)
            {
                   echo "Cannot write to text file. <br />";
            }
            fclose($handle);
            ?>

            <h2>WebLabUX Survey Consent Form </h2>

            <p>Thank you for agreeing to participate in our survey.<br />
            Please click on continue to begin the survey</p>
            <form name="form1" method="GET" action="<?php echo $siteRoot; ?>hearts.html">
                <input type="hidden" name="wlux_session" value="<?=$wlux_sid?>" />
                <input type="submit" value="continue" />
            </form>
        </div> <!-- container -->
    </body>
</html>