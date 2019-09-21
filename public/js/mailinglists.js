$(document).ready(function () {
	/* HIDE THE EDIT AND ADDNEW CARRIER ELEMENTS */
	$('#deleteListButton').click(deleteListButtonClick)
	$('#deleteListButton').hide()
	$('.deleteCheckBox').click(deleteCheckBoxClick)
	$('.masterCheck').click(masterCheckClick)

});

function deleteListButtonClick() {
	// body...

	var table = document.getElementById('mailinglistsTable')
	var tableLength = table.rows.length

	var itemAddress = $('.itemAddress')
	var deleteCheckBoxes = $('.deleteCheckBox')
	var deleteCheckBoxesIds = new Array();
	for (var i = deleteCheckBoxes.length - 1; i >= 0; i--) {
		if(deleteCheckBoxes[i].checked){
			deleteCheckBoxesIds.push(deleteCheckBoxes[i].parentNode.parentNode.id)
		}
	}

	for (var i = 0; i < deleteCheckBoxesIds.length; i++) {
		var tableRow = document.getElementById(deleteCheckBoxesIds[i])

		$.get('/deleteMailList', 
			{
				address : tableRow.id,
			},
			function deleteMailListCallBack(data, status) {
				// body...
				var tr = document.getElementById(data.address)
				tr.parentNode.removeChild(tr)
			}
		)
	}

	$('#deleteListButton').hide()

}

function deleteCheckBoxClick() {
	// body...
	if($('input:checked').length > 0){
		$('#deleteListButton').show()
	}
	else{
		$('#deleteListButton').hide()
	}
}

function masterCheckClick() {
	// body...
	var deleteCheckBoxes = $('.deleteCheckBox')
	var masterCheck = $('.masterCheck')

	for (var i = deleteCheckBoxes.length - 1; i >= 0; i--) {
		deleteCheckBoxes[i].checked = masterCheck[0].checked
	}

	if($('input:checked').length > 0){
		$('#deleteListButton').show()
	}
	else{
		$('#deleteListButton').hide()
	}
}
