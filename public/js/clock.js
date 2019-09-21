$(document).ready(function clockReady() {
	// body...
	var height = $('#clock').height();
	console.log(($('#clock')[0]).attributes)
	$('#clock').hide()
	console.log(($('#clock')[0]).attributes)
	$('#clock').show()
	console.log(($('#clock')[0]))

	setInterval(function(){
		var date = new Date();
		var time = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
		($('#clock')[0]).textContent = date.toLocaleTimeString();
		if(Number.isInteger(date.getSeconds()/10))
		{
			if($('#clock').is(':visible'))
				$('#clock').hide()
			else
				$('#clock').show()
		}
	}, 1000)
})

function getTimeZones() {
	// body...
	$.get('/zonesCheck', function getTimeZonesCallBack(data, status) {
		// body...
		console.log(data)
	})
}

function timeZone() {
	// body...
	$.get('/timeZone', function timeZoneCallBack(data, status) {
		// body...
		console.log(data)
	})
}