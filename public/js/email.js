$(document).ready(function() {
	// body...
	alert("EMAIL");
});
function sendClick() {
	// body...
	$.get('/sendEmail',
		function sendEmailCallBack(data, status) {
			// body...
			alert(data)
			alert(status)
		})
}
