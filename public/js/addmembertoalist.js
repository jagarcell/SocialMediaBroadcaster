$(document).ready(function() {
	// body...
	var accessLevel = $('.access_level')
	accessLevel[0].value = accessLevel[0].id

	$("#dialog").dialog({autoOpen : false})
	$("#opener").click(function openerClick() {
		// body...
		$( "#dialog" ).dialog( "open" )
	}) 

	$('#mastercheckbox').click(masterCheckBoxClick)
	$('.recepientcheckbox').click(recepientCheckBoxClick)

	$('#recepientDeleteButton').click(recepientDeleteButtonClick)
	$('#recepientDeleteButton').hide()

});

function masterCheckBoxClick(){
	// body...
	var recepientCheckBoxes = $('.recepientcheckbox')

	$('.recepientcheckbox').prop('checked', $('#mastercheckbox').prop('checked'))
	checkDeleteButton()
}

function recepientCheckBoxClick(){
	// body...
	checkDeleteButton()
}

function checkDeleteButton() {
	// body...
	var recepientCheckBoxes = $('.recepientcheckbox');

	if($('input.recepientcheckbox:checked').length > 0){
		$('#recepientDeleteButton').show()
	}
	else{
		$('#recepientDeleteButton').hide()
	}
	if(recepientCheckBoxes.length - $('input.recepientcheckbox:checked').length > 0){
		$('#mastercheckbox').prop('checked', false)
	}
	else{
		$('#mastercheckbox').prop('checked', true)
	}
}

function recepientDeleteButtonClick() {
	// body...
	var membersArray = []
	for (var i = 0; i < $('input.recepientcheckbox:checked').length; i++) {
		membersArray.push(($('input.recepientcheckbox:checked')[i]).value)
	}

	$.get('/deleteMembersFromAList',
		{
			address:($('#address')[0]).textContent,
			members:membersArray
		},
		function deleteMembersFromAListCallBack(data, status) {
			// body...
			$('#mastercheckbox').prop('checked', false)
			$('input.recepientcheckbox').prop('checked', false)
			checkDeleteButton();
			var members = JSON.parse(data)
			for (var i = members.length - 1; i >= 0; i--) {
				var member = members[i]
				var row = document.getElementById(member.member)
				if(member.status == 'OK')
				{
					row.parentNode.removeChild(row)
				}
				else{
					var td = document.getElementById(member.member + '_td')
					td.style.color = 'red'
				}
			}
		}
	)
}
