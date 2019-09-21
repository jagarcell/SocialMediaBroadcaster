$(document).ready(function carriersDcoumentReady(argument) {
	// body
	$(".deleteButton").click(deleteButtonClick)
	$(".updateButton").click(updateButtonClick)
	$(".addCarrierButton").click(addCarrierButtonClick)
})

// PROVIDES THE LOGIC FOR THE CARRIER INPUT CHANGES
function carrierInputChange(carrierId) {
	// body...
	$(".updateCheckBox_" + carrierId).attr('checked', true)
}

// PROVIDES THE LOGIC FOR THE CARRIER DELETE BUTTON
function deleteButtonClick() {
	// body...
	var table = document.getElementById('carriersTable')

	var deleteCheckBoxes = $(".deleteCheckBox")

	for(var i = 0; i < deleteCheckBoxes.length; i++)
	{
		if(deleteCheckBoxes[i].checked)
		{
			// REQUEST A DELETE TO THE CARRIERS MODEL	
			$.get('/removeCarrier', {
				id:deleteCheckBoxes[i].id
			}, function removeCarrierCallBack(data, status) {
				// BACK FROM THE DELETE OPERATION

				if(data.status == 'OK'){
					// IF THE CARRIER WAS SUCCESFULLY REMOVED THEN
					// REMOVE THE ROW FROM THE TABLE
					var row = document.getElementById("tr_" + data.id)
					row.parentNode.removeChild(row)
				}
			})
		}
	}
}

// PROVIDES THE LOGIC FOR THE CARRIER UPDATE BUTTON
function updateButtonClick() {
	// body...
	// GET THE UPDATE CHECK BOXES AND ...
	var updateCheckBoxes = $(".updateCheckBox")
	for (var i = 0; i < updateCheckBoxes.length; i++) {
		// VERIFY WICH ONES ARE CHECKED
		if(updateCheckBoxes[i].checked){
			// IF THE UPDATE CHECKBOX FOR THIS LINE IS CHECKED
			// THEN LET'S REQUEST A CARRIER UPDATE TO THE DATABASE
			var id = updateCheckBoxes[i].id;
			var name = $("#carrierInput_" + id).val()
			var gateway = $("#gatewayInput_" + id).val()
			var alias = $("#aliasInput_" + id).val()
			
			// REQUEST A CARRIER UPDATE 
			$.get('/updateCarrier', {
				id:id,
				name:name,
				gateway:gateway,
				alias:alias,
			}, function updateCarrierCallBack(data, status) {
				// body...
				if (data.status != 'OK') {
					alert("THE CARRIER COULD NOT BE UPDATED")
				}

				// UNCHECK THE UPDATE CHECKBOX
				$(".updateCheckBox_" + data.id)[0].checked = false;
			})
		}
	}
}

// PROVIDES THE LOGIC FOR THE CARRIER ADD BUTTON
function addCarrierButtonClick() {
	// GET THE CARRIERS HTML TABLE AND ...
	var table = document.getElementById('carriersTable')
	// ADD A NEW ROW TO IT 
	var tableRow = table.insertRow(table.rows.length - 1)

	// THESE ARE THE VALUES FOR THE NEW CARRIER
	var name = $("#newCarrier").val()
	var gateway = $("#newGateway").val()
	var alias = $("#newAlias").val()

	// REQUEST AN NEW CARRIER ADD OPERATION
	$.get('/addCarrier', 
		{
			name:name,
			gateway:gateway,
			alias:alias
		}, 
		function addCarrierCallBack(data, status) {
		// CHECK IF THE NEW CARRIER WAS SUCCESFULLY ADDED TO THE DATABASE
			if(data.status == 'OK')
			{
				// AS THE CARRIER WAS SUCCESFULLY ADDED LET'S SHOW IT IN A NEW ROW
				var id = data.id
				var name = data.name
				var gateway = data.gateway
				var alias = data.alias

				// HTML FOR THE NEW ROW
				var newRowHTML =
					'<td><input type="text" id="carrierInput_' + id + '" class="carrierInput" value="' + name + '" onchange="carrierInputChange(' + id + ')"></td>' +
					'<td><input type="text" id="gatewayInput_' + id + '" class="gatewayInput" value="' + gateway + '" onchange="carrierInputChange(' + id + ')"></td>' +
					'<td><input type="text" id="aliasInput_' + id + '" class="aliasInput" value="' + alias + '" onchange="carrierInputChange(' + id + ')"></td>' + 
					'<td class="deleteButtonColumn"><input type="checkbox" id="' + id + '" class="deleteCheckBox deleteCheckBox_' + id + '"></td>' +
					'<td class="updateButtonColumn"><input type="checkbox" id="' + id + '" class="updateCheckBox updateCheckBox_' + id + '"></td>'

				// ASSIGN THE HTML FOR THE NEW ROW
				tableRow.innerHTML = newRowHTML
				// ASSIGN THE NEW CARRIER ID TO THE HTML TABLE ROW
				tableRow.id = 'tr_' + id 

				// RESET THE INPUT FIELDS VALUES
				$("#newCarrier").val('')
				$("#newGateway").val('')
				$("#newAlias").val('')
			}
			else
			{
				// IF THERE WAS AN ERROR ADDING THE NEW CARRIER ALERT THE USER OF IT
				alert(data.status)
			}
		}
	)
}
