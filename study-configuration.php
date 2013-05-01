<?php 
//This file submits information to webservice to generate a study page
?>
	<!doctype HTML>
	<html>
	<head>
		<title></title>
	</head>
	<body>
		<form action="register_config.php" method="post">
			<fieldset>
				Condition ID &nbsp;<input type="number" name="conditionId" required><br>
				CSS URL &nbsp;<input type="url" name="cssURL" placeholder=".css url"required><br>
				Task bar CSS &nbsp;<input type="url" name="taskBarCSS" placeholder=".css url" required><br>
				Button Text &nbsp;<input type="text" name="buttonText" required><br>
				Return URL &nbsp;<input type="url" name="returnURL" required><br>
				Task Text &nbsp;<textarea name="taskText" id="taskText" cols="30" rows="10"></textarea><br>
				Tasks HTML &nbsp;<textarea name="taskHTML" id="taskHTML" cols="30" rows="10"></textarea><br>
				Tab Show Text &nbsp;<input type="text" name="tabShowText" required><br>
				Tab Hide Text &nbsp;<input type="text" name="tabHideText" required><br>
				<input type="submit" value="Submit">
			</fieldset>
		</form>
	</body>
	</html>
