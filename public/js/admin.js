$(document).ready(function () {
	/* HIDE THE EDIT AND ADDNEW CARRIER ELEMENTS */
	$(".carrierInput").hide();
	$(".new_Carrier").hide();
});

function addCarrierButtonClick() {
	// SHOW THE ADD NEW CARRIER INPUTS IN A NEW ROW
	$(".new_Carrier").show();
};

function editButtonClick(carrierName) {
	// FUNCTION TO EDIT EXISTING CARRIER ON THE DATABASE
	// HOLD THE ID CLASS IN A VARIABLE
	var _carrierName = "." + carrierName

	// FIRST HIDE THE SHOWING VALUES OF THE ACTUAL ROW THEN ... 
	$(_carrierName + "_show").hide()

	// COPY THE VALUES TO THE INPUT ELEMENTS TO BE USED FOR THE EDITING AND FINALLY ..
	$(_carrierName + "_name_edit_value").val(document.getElementById(carrierName + "_name_value").innerHTML)
	$(_carrierName + "_gateway_edit_value").val(document.getElementById(carrierName + "_gateway_value").innerHTML)
	$(_carrierName + "_alias_edit_value").val(document.getElementById(carrierName + "_alias_value").innerHTML)

	// SHOW THE INPUT ELEMENTS FOR EDITING THE VALUES
	$(_carrierName + "_edit").show()
};

function updateButtonClick(carrierName) {
	// FUNCTION TO UPDATE MODIFIED EXISTING CARRIER ON THE DATABASE

	// HOLD THE ID CLASS IN A VARIABLE
	var _carrierName = "." + carrierName

	// HOLD THE NEW VALUES IN VARIABLES
	var name = $(_carrierName + "_name_edit_value").val()
	var gateway = $(_carrierName + "_gateway_edit_value").val()
	var alias = $(_carrierName + "_alias_edit_value").val()

	// REQUEST AN UPDATE ON THE DATABASE
	$.get('/updateCarrier',
		{
			name: name,
			gateway: gateway,
			alias: alias,
		},
		function carrierUpdated(data, status) {
			// body...
			// IF THE UPDATE OPERATION WAS OK THEN ...
			if(data == 'OK'){
				// UPDATE THE SHOWING VALUES TO THE NEW ONES
				document.getElementById(carrierName + "_name_value").innerHTML = name;
				document.getElementById(carrierName + "_gateway_value").innerHTML = gateway;
				document.getElementById(carrierName + "_alias_value").innerHTML = alias;
			}

			// SHOW THE NEW VALUES AND ...
			$(_carrierName + "_show").show()

			// HIDE THE EDITING INPUT ELEMENTS
			$(_carrierName + "_edit").hide()
		}
	)
}

function addNewButtonClick(carrierName) {
	// FUNCTION TO ADD A NEW CARRIR ON THE DATABASE

	// HOLD THE ID CLASS IN A VARIABLE
	var _carrierName = "." + carrierName

	// HIDE THE NEW CARRIER ADDING ROW	
	$(".new_Carrier").hide();

	// SET NEW VALUES FOR THE ADD REQUEST
	var name = $(_carrierName + "_name_new_value").val()
	var gateway = $(_carrierName + "_gateway_new_value").val()
	var alias = $(_carrierName + "_alias_new_value").val()

	// REQUEST THE ADD
	$.get('/addCarrier',
		{
			name: name,
			gateway:gateway,
			alias:alias,
		}, 
		function carrierAdded(data, status) {

			alert(data)
			/* GET THE POSITION OF THE ROW THAT IS BEFORE THE ADDING ROW, 
			   THIS POSITION CORRESPONDS TO THE NEXT LAST VISIBLE ROW 
			   AND WIL BE USED TO INSERT THE NEW ROW TO BE SHOWN */
			
			var carriersTable = document.getElementById('carriersTableId')
			var newCarrierRow = carriersTable.insertRow(carriersTable.rows.length - 1)

			/* THIS IS THE HTML FOR THE NEW CARRIER ROW TO BE SHOWN IN THE TABLE AS ALREADY REGISTERED */
			newCarrierRow.innerHTML = 
            '<td class="dataColumn ' + name + '_show  ' + name + '_name_value">' + name + '</td>' +
            '<td class="dataColumn  ' + name + '_show  ' + name + '_gateway_value">' + gateway + '</td>' +
            '<td class="dataColumn  ' + name + '_show  ' + name + '_alias_value">' + alias + '</td>' +
            '<td class="buttonColumn  ' + name + '_show">' +
                '<input type="button" name="" value=" Edit " class="buttonRight" title="TO CHANGE THE CONTENT OF THIS ROW" onclick="editButtonClick(' + "'"  + name + "'" + ')"></td>' +
            '<td class="buttonColumn ' + name + '_show">' +
                '<input type="checkbox" name="editButton" class="buttonRight"></td>' +

            '<td class="dataColumn ' + name + '_edit carrierInput " style="display:none;">' +
                '<input type="text" class="inputNoBorder ' + name + '_name_edit_value"></td>' +
            '<td class="dataColumn ' + name + '_edit carrierInput" style="display:none;">' +
                '<input type="text" class="inputNoBorder ' + name + '_gateway_edit_value"></td>' +
            '<td class="dataColumn ' + name + '_edit carrierInput" style="display:none;">' +
                '<input type="text" class="inputNoBorder ' + name + '_alias_edit_value"></td>' +
            '<td class="buttonColumn ' + name + '_edit carrierInput" style="display:none;">' +
                '<input type="button" class="buttonRight ' + name + '_edit" value="Update" title="TO UPDATE THE CHANGES MADE" onclick="updateButtonClick(' + "'" + name + "'" + ')"></td>'
		}
	)
}

function deleteSelectedCarriers() {
	alert("DELETE SELECTED CARRIERS")
}