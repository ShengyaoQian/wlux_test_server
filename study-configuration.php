<?php 
//This file submits information to webservice to generate a study page
$file = '../config_file/config.txt';

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
				Condition ID &nbsp;<input id="conditionId" type="number" name="conditionId" required><br>
				CSS URL &nbsp;<input id="cssURL" type="url" name="cssURL" placeholder=".css url"required><br>
				Task bar CSS &nbsp;<input id="taskBarCSS" type="url" name="taskBarCSS" placeholder=".css url" required><br>
				Button Text &nbsp;<input id="buttonText" type="text" name="buttonText" required><br>
				Return URL &nbsp;<input id="returnURL" type="url" name="returnURL" required><br>
				Task Text &nbsp;<textarea id="taskText" name="taskText" id="taskText" cols="30" rows="10"></textarea><br>
				Tasks HTML &nbsp;<textarea id="taskHTML" name="taskHTML" id="taskHTML" cols="30" rows="10"></textarea><br>
				Tab Show Text &nbsp;<input id="tabShowText" type="text" name="tabShowText" required><br>
				Tab Hide Text &nbsp;<input id="tabHideText" type="text" name="tabHideText" required><br>
				<input id="submitbtn" type="button" value="Submit">
			</fieldset>
		</form>
		<a href="../config_file/config.txt">Downlaod Your Configuration here</a>
	</body>
	</html>
