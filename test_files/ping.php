<?php
header("content-type: text/javascript");
$qs = $_GET['jsonp'];
if (strlen($qs) > 0) {
	echo $qs.'({"key": "value"});';
} else {
	echo '{"key": "value"}';
}
?>
