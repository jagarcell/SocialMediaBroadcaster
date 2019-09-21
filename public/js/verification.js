$(document).ready(
	function verificationPanelDocumentReady() {
		// SET THE APROPIATE COLOR FOR EACH STATUS
		$(".VERIFIED").css('color', 'green')
		$(".INVALID").css('color', 'red')

		// ADD THE EVENTS FOR UPDATE, DELETE ADD AND VERIFY BUTTONS
		$(".deleteButtonClick").click(deleteButtonClick)
		$(".updateButtonClick").click(updateButtonClick)
		$(".addButton").click(addButtonClick)
		$(".verifyPendingNumbersButton").click(verifyPendingButtonClick)
		$(".populateWithSecuenceButton").click(populateWithSecuenceButtonClick)
		$(".saveResultsButton").click(saveResultsButtonClick)

		$( "#groupChangeDialog" ).dialog({ autoOpen: false });
		$( "#groupChangeDialog" ).dialog({
		  position: { my: "left top", at: "left bottom", of: $(".changeGroupButton") }
		});
		$(".changeGroupButton").click(function() {
			var groupInputs = $('.groupInput')
			if(groupInputs.length > 0){
		  		$( "#groupChangeDialog" ).dialog( "open" )
		  	}
		});

		$('.changeGroupAcceptButton').click(changeGroupAcceptButtonClick)
	}
)

function addButtonClick() {
	// FIRST LET'S TAKE THE PHONE NUMBER FROM THE PHONEINPUT ELEMENT
	var phoneNumber = $("#phoneInput").val()

	addNumberToVerify(phoneNumber)

	document.getElementById("phoneInput").focus()
}

function addNumberToVerify(phoneNumber, tableRowIndex = 0) {
	// body...

	// REQUEST THE NEW PHONE NUMBER ADDITION
	$.get('/addNumberToVerify',
		{
			phonenumber:phoneNumber,
		},
		function addNumberToVerifyResult(data, status) {
			if(data != 'ERROR'){
				var addedNumber = JSON.parse(data)
				// IF THE DATABASE UPDATE WAS OK THE OBJECT DATA WILL HAVE
				// THE ROW'S DATA IN JSON FORMAT

				// FIRST LET'S TAKE THE NUMBERS TO VERIFY TABLE ELEMENT
				var numbersToVerifyTable = document.getElementById('numbersToVerifyTable')

				// CLEAR THE PHONE INPUT FOR THE NEXT INCLUDE OPERATION
				$("#phoneInput").val('')

				// CREATE A ROW BEFORE THE PHONE INPUT ROW TO SHOW THE NEW ADDED ROW
				var tableRow
				if(tableRowIndex == 0 || tableRowIndex > numbersToVerifyTable.rows.length - 1){
					tableRow = numbersToVerifyTable.insertRow(numbersToVerifyTable.rows.length - 1)
				}
				else{

					tableRow = numbersToVerifyTable.insertRow(tableRowIndex)
				}

				tableRow.innerHTML = 
						'<td id="phoneColumn_' + addedNumber.id + '" class="phoneColumn"><input class="phoneInput" type="text" name="" value="' + addedNumber.phonenumber + '" id="phoneInputChangedCheckbox_' + addedNumber.id + '"></td>' +
						'<td id="statusColumn_' + addedNumber.id + '" class="statusColumn statusCell ' + addedNumber.status + '">' + addedNumber.status + '</td>' +
						'<td class="checkButtonColumn"><input type="checkbox" name="" id="' + addedNumber.id + '"  class="deleteCheckBox deleteCheckBox_' + addedNumber.id + '"></td>' +
						'<td class="checkButtonColumn"><input type="checkbox" name="" id="' + addedNumber.id + '" class="updateCheckBox phoneInputChangedCheckbox_' + addedNumber.id + '"></td>'

				tableRow.id = 'tr_' + addedNumber.id

				// ADD THE PHONE INPUT CHANGE EVENT TO THE NEW ADDED ROW
				$("#phoneInputChangedCheckbox_" + addedNumber.id).change(function phoneInputChangeCall() {
					// body...
					phoneInputChange('phoneInputChangedCheckbox_' + addedNumber.id)
				})
			}
			else{
			// IF THERE WAS AN ERROR UPDATING THE DATA BASE WE WILL REPORT IT
				alert("THE DATABASE COULDN'T BE UPDATED")
			}
		}
	)
}

function verifyPendingButtonClick() {

	$.get('/verifyPhoneNumbers', function verifyPhoneNumbersCallBack(pendingNumbers) {
		// body...
		var verifiedNumbersTable = document.getElementById("verifiedNumbersTable")

		// ON THE RETURN FROM THE REQUEST LET'S DELETE
		// ALL THE ROWS WITH THE VERIFIED STATUS FROM
		// THE PENDING TO VERIFY HTML TABLE
		for (var i = 0; i < pendingNumbers.length; i++) {
			// GET THE CORRESPONDING HTML ROW
			var row = document.getElementById("tr_" + pendingNumbers[i].id)
			if(pendingNumbers[i].status == 'VERIFIED' && pendingNumbers[i].carrier.length > 0){
				// IF THE phone number WAS VERIFIED AS VALID THEN LET'S
				// DELETE THE CORRESPONDING <tr id="tr_id" FROM THE TABLE
				row.parentNode.removeChild(row)

				// AND ADD A NEW ROW TO THE VERIFIED NUMBERS HTML TABLE
				var rowVerified = verifiedNumbersTable.insertRow(1);
				rowVerified.innerHTML = 
					'<td class="phoneVerifiedColumn verifiedPhoneNumber">' + pendingNumbers[i].phonenumber + '</td>' +
					'<td class="carrierVerifiedColumn carrierVerifiedName" id="' + i + '">' + pendingNumbers[i].carrier + '</td>' +
					'<td class="groupVerifiedColumn"><input type="text" class="groupInput" value="DEFAULT"></td>' +
					'<td class="gatewayVerifiedColumn"><input type="text" class="gatewayInput gatewayInput_' + pendingNumbers[i].carrier + '" id="gatewayInput_' + pendingNumbers[i].id + '" value="' + pendingNumbers[i].gateway + '" onchange="gatewayInputChange(\'' + pendingNumbers[i].carrier + '\', ' + pendingNumbers[i].id + ')"></td>'
				rowVerified.id = "tr_" + i;
			}
			else{
				// IF THE PHONE NUMBER RESULTED AN INVALID THEN
				// LET'S SET THIS STATUS IN THE CORRESPONDING ROW
				// OF THE NUMBERS TO VERIFY HTML TABLE RED COLORED
				$("#statusColumn_" + pendingNumbers[i].id).css('color', 'red')
				$(".deleteCheckBox_" + pendingNumbers[i].id).attr('checked', true)
				if(pendingNumbers[i].carrier.length == 0){
					$("#statusColumn_" + pendingNumbers[i].id)[0].innerText = 'NO CARRIER'
				}
				else{
					$("#statusColumn_" + pendingNumbers[i].id)[0].innerText = pendingNumbers[i].status
				}
			}
		}
	})
}

function phoneInputChange(idClass) {
	// TAKE ACTIONS FOR THE PHONE NUMBER EDITION

	// CHECK THE UPDATED CHECKBOX INDICATING THAT
	// WILL BE UPDATED DURING THE UPDATE OPERATION
	$("." + idClass).attr('checked', true)
}

function deleteButtonClick() {
	// FIRST LET'S TAKE THE NUMBERS TO VERIFY TABLE ELEMENT
	var numbersToVerifyTable = document.getElementById('numbersToVerifyTable')

	// NOW THE DELETE CHECKBOXES COLLECTION
	var deleteCheckBoxes = $('.deleteCheckBox')

	// THE deleteCheckBoxes HAS A DIFFERENT LENGTH THAN THE numbersToVerifyTable 
	// BECAUSE THE TABLE INCLUDES THE HEADER AND FOOTER ROW.
	// WE GET AN OFFSET TO KNOW THE REAL POSITION OF THE ROW TO BE REMOVED FROM THE SCREEN
	var rowOffSet = numbersToVerifyTable.rows.length - deleteCheckBoxes.length

	// SCAN THE CHECKBOXES COLLECTION TO VERIFY WICH IS FLAGGED FOR ROW DELETE
	for (var i = deleteCheckBoxes.length - 1; i >= 0 ; i--) {

		//IF THIS IS CHECKED FOR DELETION
		if (deleteCheckBoxes[i].checked) {
			// THEN DELETE IT
			$.get('removePhoneNumber',
				{id:deleteCheckBoxes[i].id},
				function removePhoneNumberCallBack(data) {

					// data HAS THE STATUS OF THE DELETE OPERATION ON THE DATABASE
					// AND THE ID OF THE TABLE ROW TO BE REMOVED FROM THE TABLE
					if(data.status == 'OK')
					{
						// IF THE DELETE ON THE DATABASE WAS OK THEN REMOVE
						// THE CORRESPONDING <tr id="tr_id" FROM THE TABLE
						var row = document.getElementById("tr_" + data.id)
						row.parentNode.removeChild(row)
					}
				}
			)
		}
	}
}

function updateButtonClick() {
	// UPDATE THE PHONE NUMBERS THAT ARE CHECKED FOR UPDATE

	// GET THE UPDATE CHECK BOXES
	var updateCheckBoxes = $(".updateCheckBox")

	// SCAN THE UPDATE CHECK BOXES TO
	// SEARCH FOR THE CHECKED ONES
	for (var i = 0; i < updateCheckBoxes.length; i++) {
		// IF CHECKED FOR UPDATE THEN ...
		if(updateCheckBoxes[i].checked){
			var phonenumber = $("#phoneInputChangedCheckbox_" + updateCheckBoxes[i].id).val()
			// UPDATE IT
			$.get('updatePhoneNumber',
				{
					id:updateCheckBoxes[i].id,
					phonenumber:phonenumber,
				},
				function updatePhoneNumberCallBack(data) {
					// CHECK THE UPDATE RESULT
					if(data.status == "OK"){
						// IF THE UPDATE WAS OK THEN UNCHECK THE UPDATE CHECK BOX
						$(".phoneInputChangedCheckbox_" + data.id).attr('checked', false)
					}
				}
			)
		}
	}
}


function saveResultsButtonClick() {
	// body...
	// GET THE COLLECTIONS NEEDED FOR THE UPDATE OPERATION
	var verifiedPhoneNumbers = $(".verifiedPhoneNumber")
	var carrierNames = $(".carrierVerifiedName")
	var groupInputs = $(".groupInput")
	var gatewayInputs = $(".gatewayInput")

	// WALK THE COLLECTIONS TO UPDATE THE VALUES IN THE DATABASE
	for (var i = 0; i < groupInputs.length; i++) {
		// REQUEST AN UPDATE
		$.get('/updateCarrierGateway', {
				alias:carrierNames[i].innerText,
				gateway:gatewayInputs[i].value,
				rowId:carrierNames[i].id,
			}, function updateCarrierGatewayCallback(data, status) {
				// body...
				// WE USE THE rowId THAT WAS SENT TO THE REQUEST
				// IN ORDER TO DELETE THE CORRECT HTML TABLE ROW
				var row = document.getElementById("tr_" + data.rowId)
				row.parentNode.removeChild(row)
			}
		)

		$.get('/updatePhone',
			{group:groupInputs[i].value,
			 phonenumber:verifiedPhoneNumbers[i].innerText},
			function updatePhoneCallBack(data, status) {
				// body...

			}
		)
	}
}


function gatewayInputChange(gatewayCarrier, id) {
	// body...
	// WHEN A gateway IS CHANGED BY THE USER WE CHANGE THIS gateway
	// IN ALL THE CARRIERS WITH THE SAME NAME IN THE RESULTS TABLE
	$(".gatewayInput_" + gatewayCarrier).val($("#gatewayInput_" + id).val())
}

function populateWithSecuenceButtonClick() {
	// body...
	var numbersToVerifyTable = document.getElementById("numbersToVerifyTable")
	var tableLength = numbersToVerifyTable.rows.length - 1

	var phoneInputs = $(".phoneInput")
	var phoneNumbersSeed = parseInt(phoneInputs[phoneInputs.length - 2].value)

	if(phoneInputs.length > 1){
		var phoneNumbersSeed = parseInt(phoneInputs[phoneInputs.length - 2].value)
		for (var i = 0; i < 10; i++) {
			addNumberToVerify(phoneNumbersSeed + i + 1, tableLength + i)
		}
	}
}

function changeGroupAcceptButtonClick() {
	// body...
	var groupInputs = $('.groupInput')
	var newGroupName = $('#groupNameToChange').val()
	$( "#groupChangeDialog" ).dialog( "close" )

	for(var i = 0; i < groupInputs.length; i++){
		groupInputs[i].value = newGroupName
	}
}