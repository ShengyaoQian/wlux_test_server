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
    $wlux_sid = fread($ini_handle, filesize($filename));

//  echo 'SessionID = '.$wlux_sid.'</br></br>';

    fclose($ini_handle);
    //done obtaining initially present data

    //write new data to the file, along with the old data
	$handle = fopen($filename, "w+");
    $new_sid = $wlux_sid + 1;

    if (fwrite($handle, $new_sid) === false)
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
