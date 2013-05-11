<?php 
//This file submits information to webservice to generate a study page
	include 'config_files.php';
?>
	<!doctype HTML>
	<html>
	<head>
		<title></title>
		<script src="jquery.js"></script>
		<script src="study-configuration.js"></script>
	</head>
	<body>
		<form>
			<fieldset>
			<?php 

				$file = $CONFIG_FILE_PATH.$CONFIG_FILE_NAME;
				if (file_exists($file)) {
					$defaults = unserialize(file_get_contents($file));
				?>
					Condition ID &nbsp;<input id="conditionId" type="number" name="conditionId" value=<?php print $defaults['conditionId'] ?> required><br>
					CSS URL &nbsp;<input id="cssURL" type="url" name="cssURL" placeholder=".css url" value=<?php print $defaults['cssURL'] ?> required><br>
					Task bar CSS &nbsp;<input id="taskBarCSS" type="url" name="taskBarCSS" placeholder=".css url" value=<?php print $defaults['taskBarCSS'] ?> required><br>
					Button Text &nbsp;<input id="buttonText" type="text" name="buttonText" value=<?php print $defaults['buttonText'] ?> required><br>
					Return URL &nbsp;<input id="returnURL" type="url" name="returnURL" value=<?php print $defaults['returnURL'] ?> required><br>
					Task Text &nbsp;<textarea id="taskText" name="taskText" id="taskText" cols="30" rows="10" ><?php print $defaults['taskText'] ?> </textarea><br>
					Tasks HTML &nbsp;<textarea id="taskHTML" name="taskHTML" id="taskHTML" cols="30" rows="10" ><?php print $defaults['taskHTML'] ?> </textarea><br>
					Tab Show Text &nbsp;<input id="tabShowText" type="text" name="tabShowText" value=<?php print $defaults['tabShowText'] ?> required><br>
					Tab Hide Text &nbsp;<input id="tabHideText" type="text" name="tabHideText" value=<?php print $defaults['tabHideText'] ?> required><br>
					<input id="submitbtn" type="button" value="Submit">
				<?php
				} else {
				?>
					Condition ID &nbsp;<input id="conditionId" type="number" name="conditionId" required><br>
					CSS URL &nbsp;<input id="cssURL" type="url" name="cssURL" placeholder=".css url" required><br>
					Task bar CSS &nbsp;<input id="taskBarCSS" type="url" name="taskBarCSS" placeholder=".css url" required><br>
					Button Text &nbsp;<input id="buttonText" type="text" name="buttonText" required><br>
					Return URL &nbsp;<input id="returnURL" type="url" name="returnURL" required><br>
					Task Text &nbsp;<textarea id="taskText" name="taskText" id="taskText" cols="30" rows="10" ></textarea><br>
					Tasks HTML &nbsp;<textarea id="taskHTML" name="taskHTML" id="taskHTML" cols="30" rows="10" ></textarea><br>
					Tab Show Text &nbsp;<input id="tabShowText" type="text" name="tabShowText" required><br>
					Tab Hide Text &nbsp;<input id="tabHideText" type="text" name="tabHideText" required><br>
					<input id="submitbtn" type="button" value="Submit">
				<?php
				}
				?>
			
			</fieldset>
		</form>
		<a href="<?php echo $CONFIG_FILE_PATH.$CONFIG_FILE_NAME?>">Right click to download configuration</a>
	</body>
	</html>
