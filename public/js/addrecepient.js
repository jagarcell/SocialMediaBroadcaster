$(document).ready(function addRecepientReady() {
	// body...
	$('.masterCheckBox').click(masterCheckBoxClick)
	$('.deleteRecepientCheckBox').click(deleteRecepientCheckBoxClick)
	$('#phonestarting').attr('hidden', '')
	$('#phonefilter').change(phonefilterchange)
	$('.showRecepients').click(showRecepients)
	$('#deleteRecepients').click(deleteRecepients)
	$('#deleteRecepients').hide()
	$('.addRecepientsCheckBox').click(addRecepientsCheckBoxClick)
	$('#addRecepients').click(addRecepientsClick)
	$('#addRecepients').hide()

})

function masterCheckBoxClick(argument) {
	// body...
	$('.addRecepientsCheckBox').prop('checked', $('.masterCheckBox').prop('checked'))
	checkAddButton()
}

function deleteRecepientCheckBoxClick(argument) {
	// body...
}

function checkDeleteButton() {
	// body...
}

function phonefilterchange() {
	// body...
	if(($('#phonefilter')[0]).value == 'Starting'){
		$('#phonestarting').removeAttr('hidden')
	}
	else{
		$('#phonestarting').attr('hidden', '')
		$('#phonestarting').val('')
	}
}

function showRecepients() {
	// body...
	var phonenumber = $('#phonestarting').val()
	var carrier = $('#carrier').val()

	$.get('/getRecepients', 
		{
			phonenumber:phonenumber,
			carrier:carrier
		},
		function getRecepientsCallBack(data, status) {
			// body...
			$('.recepientRow').remove()
			for (var i = data.length - 1; i >= 0; i--) {

				var recepientsTable = document.getElementById('recepientsTable')
				var recepientRow = recepientsTable.insertRow()
				recepientRow.classList.add('recepientRow')

				recepientRow.innerHTML =
					'<tr class="recepientRow">' +
						'<td><input type="checkbox" class="addRecepientsCheckBox"></td>' +
						'<td id="td_phonenumber_' + data[i].phonenumber + '">' + data[i].phonenumber + '</td>' +
						'<td id="td_carrier_' + data[i].phonenumber + '">' + data[i].carrier + '</td>' +
					'</tr>'
				recepientRow.id = data[i].phonenumber
			}
			$('.addRecepientsCheckBox').click(addRecepientsCheckBoxClick)
		})
	$('#addRecepients').hide()

}

function deleteRecepients() {
	// body...
	var rowsToDelete = []
	var address = ($('#address')[0]).innerText

	for (var i = $('input.deleteRecepientCheckBox:checked').length - 1; i >= 0; i--) {
		rowsToDelete.push((($('input.deleteRecepientCheckBox:checked')[i]).parentNode.parentNode).id)
//		var row = ($('input.deleteRecepientCheckBox:checked')[i]).parentNode.parentNode
//		row.parentNode.parentNode.deleteRow(row.rowIndex)
	}
	$.get('/deleteMembersFromAList',{
		address:address,
		members:rowsToDelete
	}, function deleteMembersFromAList(data, status){
		console.log(JSON.parse(data))
	})
}

function addRecepientsClick() {
	// body...
	var recepientsToAdd = $('input.addRecepientsCheckBox:checked')
	var address = ($('#address')[0]).innerText

	for (var i = recepientsToAdd.length - 1; i >= 0; i--) {
		$.get('/updatePhoneMailList',{
			phonenumber:recepientsToAdd[i].parentNode.parentNode.id,
			maillist:address
		}, function updatePhoneMailListCallBack(data, status) {
			// body...
			if(data.status == 'OK'){
				$('#' + data.phonenumber).remove()
			}
			else{
				$('#td_phonenumber_' + data.phonenumber).css('color', 'red')
				$('#td_carrier_' + data.phonenumber).css('color', 'red')
			}
			checkAddButton();
		})
	}
}

function addRecepientsCheckBoxClick(argument) {
	// body...
	if($('input.addRecepientsCheckBox:checked').length == $('.addRecepientsCheckBox').length){
		$('.masterCheckBox').prop('checked', true)
	}
	else{
		$('.masterCheckBox').prop('checked', false)
	}
	checkAddButton()
}

function checkAddButton() {
	// body...
	if($('input.addRecepientsCheckBox:checked').length > 0){
		$('#addRecepients').show()
	}
	else{
		$('#addRecepients').hide()
	}
}