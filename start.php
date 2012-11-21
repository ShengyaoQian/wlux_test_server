<html>
<head></head>
<body>
	<table width="400" border="0" align="center" cellpadding="3"
		cellspacing="1">
		<tr>
			<td>
				<strong></br></br></br>WebLabUX Survey Consent Form </strong>
			</td>
		</tr>
	</table>

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

    //write new data to the file, along with the old data
	$handle = fopen($filename, "w");
	
	//new incremented session ID
    $new_sid = $wlux_sid + 1;
    
    //incrementing condition - 1,2 or 3
    $condition = $condition + 1;
    
    if($condition == 4)
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


	<table width="400" border="0" align="center" cellpadding="0"
		cellspacing="1">
		<tr>
			<td>
				<form name="form1" method="GET" action="hearts.php">
					<table width="100%" border="0" cellspacing="1" cellpadding="3">
						<tr>
							<td width="100%"></br>Thank you for agreeing to participate in our survey. </br>Please click on continue to begin the survey</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="Submit" value="Continue">
							</td>
						</tr>
					</table>
					<input type="hidden" name="sid" value="<?=$wlux_sid?>" />
				</form>
			</td>
		</tr>
	</table>
</body>
</html>
