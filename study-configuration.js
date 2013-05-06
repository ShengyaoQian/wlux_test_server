$(function(){
	$('#submitbtn').click(getJSON);
});

//Query's register_config.php to get JSON data
function getJSON () {
	$.ajax('register_config.php', {
		data: {
			'conditionId': $('#conditionId').val(),
			'cssURL': $('#cssURL').val(),
			'taskBarCSS': $('#taskBarCSS').val(),
			'buttonText': $('#buttonText').val(),
			'returnURL': $('#returnURL').val(),
			'taskText': $('#taskText').val(),
			'taskHTML': $('#taskHTML').val(),
			'tabShowText': $('#tabShowText').val(),
			'tabHideText': $('#tabHideText').val()
		}
	});
}
