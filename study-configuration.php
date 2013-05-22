<?php 
//This file submits information to webservice to generate a study page
include 'config_files.php';
error_reporting(E_STRICT);
?>
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
					echo($defaults);
				}
				?>
					<!--
					If a config file exists on the server this form will load with the configuration
					already in the fields. If not then the fields will be blank
					-->
					Condition ID &nbsp;<input id="conditionId" type="number" name="conditionId" value=<?php if($defaults){ print $defaults['conditionId']; } ?> required><br>
					CSS URL &nbsp;<input id="cssURL" type="url" name="cssURL" placeholder=".css url" value=<?php if($defaults){ print $defaults['cssURL']; }?> required><br>
					Task bar CSS &nbsp;<input id="taskBarCSS" type="url" name="taskBarCSS" placeholder=".css url" value=<?php if($defaults){ print $defaults['taskBarCSS']; }?> required><br>
					Button Text &nbsp;<input id="buttonText" type="text" name="buttonText" value=<?php if($defaults){ print $defaults['buttonText']; }?> required><br>
					Return URL &nbsp;<input id="returnURL" type="url" name="returnURL" value=<?php if($defaults){ print $defaults['returnURL']; }?> required><br>
					Task Text &nbsp;<textarea id="taskText" name="taskText" id="taskText" cols="30" rows="10" ><?php if($defaults){ print $defaults['taskText']; }?> </textarea><br>
					Tasks HTML &nbsp;<textarea id="taskHTML" name="taskHTML" id="taskHTML" cols="30" rows="10" ><?php if($defaults){ print $defaults['taskHTML']; }?> </textarea><br>
					Tab Show Text &nbsp;<input id="tabShowText" type="text" name="tabShowText" value=<?php if($defaults){ print $defaults['tabShowText']; }?> required><br>
					Tab Hide Text &nbsp;<input id="tabHideText" type="text" name="tabHideText" value=<?php if($defaults){ print $defaults['tabHideText']; }?> required><br>
					Automatic Condition ID <input type="checkbox" id="autoconditionid" name="autocondditionid"> <br>
					<input id="submitbtn" type="button" value="Submit">
				
			</fieldset>
		</form>
		<form action="upload_file.php" method="post" enctype="multipart/form-data">
			<label for="file">Filename:</label>
			<input type="file" name="file" id="file"><br>
			<input type="submit" name="submit" value="Submit">
		</form>
		<a href="<?php echo $file ?>">Right click to download configuration</a>
	</body>
	</html>
