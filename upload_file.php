<?php
if ($_FILES["file"]["error"] > 0){
	echo "Error: " . $_FILES["file"]["error"] . "<br>";
} else {
    move_uploaded_file($_FILES["file"]["tmp_name"], "../config_file/config.txt");
    header("Location: study-configuration.php");
}
?>