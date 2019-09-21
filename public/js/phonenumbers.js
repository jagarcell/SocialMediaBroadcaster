$(document).ready(
	function phoneNumbersDocumentReady() {
		// body...
		$(".deleteButton").click(deleteButtonClick)
		$(".updateButton").click(updateButtonClick)

		$( "#dialog" ).dialog({ autoOpen: false });
		$( "#dialog" ).dialog({
		  position: { my: "right bottom", at: "right top", of: $("#opener") }
		});

		$('.updateCheckbox').change(updateCheckboxChanged)

		$( "#opener" ).click(function() {
			if($('#dialog').dialog('isOpen')){
				$('#dialog').dialog("close")

				addNewPhoneRow(
					{
						newPhoneNumber:$("#newPhoneNumberInput").val(),
						newCarrier:$("#newCarrierInput").val(),
						newGroup:$("#newGroupInput").val(),
						newSuscriber:$("#newSuscriberInput").val()
					})
			}
			else{
				$('#dialog').dialog("open")
			}
		});

		$('#propagateGroup').hide()

		$('#propagateGroupDialog').dialog({autoOpen: false})
		$("#propagateGroupDialog").dialog({
			position: {my: "left bottom", at: "left top", of: $("#propagateGroup")}
		})

		$('#propagateGroup').click(function() {
			// body...
			if($('#propagateGroupDialog').dialog('isOpen')){
				$('#propagateGroupDialog').dialog('close')
				$("#propagateGroup").hide()

				var updateCheckedBoxes = $('input:checked')
				for (var i = 0; i < updateCheckedBoxes.length; i++) {
					$("#groupInput_" + updateCheckedBoxes[i].id).val($('#propGroupName').val())
				}

			}
			else{
				$('#propagateGroupDialog').dialog('open')
			}
		})
	}
)

function pageUnload() {
	// body...
	alert("unload")
}
function deleteButtonClick() {
	// body...
	alert("deleteButtonClick")
}

function updateButtonClick() {
	// body...
	var updateCheckBoxes = $(".updateCheckbox")
	for (var i = 0; i < updateCheckBoxes.length; i++) {
		if(updateCheckBoxes[i].checked){
			var id = updateCheckBoxes[i].id
			var phonenumber = $("#phoneNumberInput_" + id).val()
			var carrier = $("#carrierInput_" + id).val()
			var group = $("#groupInput_" + id).val()
			var suscriber = $("#suscriberInput_" + id).val()
			$.get('/updatePhoneRegister', {
				id: id,
				phonenumber: phonenumber,
				carrier: carrier,
				group: group,
				suscriber: suscriber,
			}, function updatePhoneRegisterCallBack(data, status) {
				// body...
				$(".updateCheckbox_" + data.id).attr('checked', false)
			})
		}
	}
	$('#propGroupName').val('')
	$('#propagateGroup').hide()
	$('#propagateGroupDialog').dialog('close')
}

function phoneNumberInputChange($id) {
	// body...
	$(".updateCheckbox_" + $id).attr('checked', true)
}

function carrierInputChange($id) {
	// body...
	$(".updateCheckbox_" + $id).attr('checked', true)
}

function groupInputChange($id) {
	// body...
	$(".updateCheckbox_" + $id).attr('checked', true)
}

function suscriberInputChange($id) {
	// body...
	$(".updateCheckbox_" + $id).attr('checked', true)
}

function addNewButtonClick(lastPageLink) {
	// body...

	var id = 'newRow_' + uniqueId()

	var phoneNumbersTable = document.getElementById('phoneNumbersTable')
	var nRows = phoneNumbersTable.rows.length
	var newRow = phoneNumbersTable.insertRow(nRows)

	newRow.innerHTML =
					'<td>' +
						'<input type="text" id="phoneNumberInput_' + id + '" class="tableInput" value="" onchange="phoneNumberInputChange(\'' + id + '\')">' +
					'</td>' +
					'<td>' +
						'<input type="text" id="carrierInput_' + id + '" class="tableInput" value="" onchange="carrierInputChange(\'' + id + '\')">' +
					'</td>' +
					'<td>' +
						'<input type="text" id="groupInput_' + id + '" class="tableInput" value="" onchange="groupInputChange(\'' + id + '\')">' +
					'</td>' +
					'<td>' +
						'<input type="text" id="suscriberInput_' + id + '" class="tableInput" value="" onchange="suscriberInputChange(\'' + id + '\')">' +
					'</td>' +
					'<td>' +
						'<input type="checkbox" id="' + id + '" class="deleteCheckbox deleteCheckbox_0' + id + '">' +
					'</td>' +
					'<td>' +
						'<input type="checkbox" id="' + id + '" class="updateCheckbox updateCheckbox_' + id + '">' +
					'</td>'

}

function addNewPhoneRow(values) {
	// body...

	$.get('/addPhoneRegister',
		{
			phonenumber : values.newPhoneNumber,
			carrier : values.newCarrier,
			group : values.newGroup,
			suscriber : values.newSuscriber,
		},
		function addPhoneRegisterCallBack(data, status) {
			// body...
			console.log(data)
			if(data.status == 'OK'){
				var id = 'newRow_' + uniqueId()
	
				var phoneNumbersTable = document.getElementById('phoneNumbersTable')
				var nRows = phoneNumbersTable.rows.length
				var newRow = phoneNumbersTable.insertRow(nRows)
	
				newRow.innerHTML =
					'<td>' +
						'<input type="text" id="phoneNumberInput_' + id + '" class="tableInput" value="' + values.newPhoneNumber + '" onchange="phoneNumberInputChange(\'' + id + '\')">' +
					'</td>' +
					'<td>' +
						'<input type="text" id="carrierInput_' + id + '" class="tableInput" value="' + values.newCarrier + '" onchange="carrierInputChange(\'' + id + '\')">' +
					'</td>' +
					'<td>' +
						'<input type="text" id="groupInput_' + id + '" class="tableInput" value="' + values.newGroup + '" onchange="groupInputChange(\'' + id + '\')">' +
					'</td>' +
					'<td>' +
						'<input type="text" id="suscriberInput_' + id + '" class="tableInput" value="' + values.newSuscriber + '" onchange="suscriberInputChange(\'' + id + '\')">' +
					'</td>' +
					'<td>' +
						'<input type="checkbox" id="' + id + '" class="deleteCheckbox deleteCheckbox_0' + id + '">' +
					'</td>' +
					'<td>' +
						'<input type="checkbox" id="' + id + '" class="updateCheckbox updateCheckbox_' + id + '">' +
					'</td>'
			}
		}
	)
}

function uniqueId() {
	// body...

	if(uniqueId.id === undefined){
		uniqueId.id = 9999999999999
	}
	return --uniqueId.id
}

function updateCheckboxChanged() {
	// body...
	var updateCheckedBoxes = $('input:checked')
	if(updateCheckedBoxes.length > 0){
		$('#propagateGroup').show()
	}
	else{
		$('#propagateGroup').hide()
		$('#propagateGroupDialog').dialog('close')
	}
}