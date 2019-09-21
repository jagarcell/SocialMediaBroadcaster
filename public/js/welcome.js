$(document).ready(function() {
	// body...
	ipcheck()
	$( "#dialog" ).dialog({ autoOpen: false });
	$( "#dialog" ).dialog({
	  position: { my: "right bottom", at: "right top", of: $("#addImagesButton") }
	});
	$( "#dialog" ).dialog({
		close: function( event, ui ) {
		// THE CODE TO PROCCESS THE DIALOG RESULTS GOES HERE

			// SET THE VARIABLE TO KEEP TRACK OF THE IMAGEZONE ID
			var nextImageArea = $('#nextImageArea').val()

			// GET THE DIV ELEMENT THAT HOLDS THE TEXT AND IMAGES ELEMENTS 
			var layoutDiv = document.getElementById('layoutDiv')

			// ADD AN IMAGE ELEMENT TO THE LAYOUT IN FORM OF TABLE
			layoutDiv.innerHTML += 
                '<table class="imagesTable" id="imagesTableZone' + nextImageArea + '">' +
                '</table>'

            // GET THE NEW ADDED IMAGE TABLE ELEMENT AS A JQUERY ELEMENT
			var imagesTableZone = $('#imagesTableZone' + nextImageArea)

			// SET THE VARIABLES THAT SPECIFY THE ROWS AND COLUMNS FOR THE IMAGE TABLE
			var numberOfColumns = $('#numberOfColumns').val()
			var numberOfRows = $('#numberOfRows').val()

			$('#numberOfUploadedRows').val(numberOfRows)
			$('#numberOfUploadedColumns').val(numberOfColumns)


			// CREATE THE IMAGES TABLES WITH numberOfRows AND numberOfColumns
			for (var r = 0; r < numberOfRows; r++) {
				var row = ($('#imagesTableZone' + nextImageArea)[0]).insertRow(-1)
				for (var i = 0; i < numberOfColumns; i++) {
					var td = row.insertCell(-1)
					td.innerHTML = 
					'<form action="/fileUpload" method="post" enctype="multipart/form-data"class="dropzone uploadDiv" id="imagesDropZone'  + nextImageArea + r + i +'">' +
					'<input type="hidden" name="_token" value="' + document.getElementsByName('_token')[0].value + '">' +
					'</form>' +
					'<textarea class="imageTag" onchange="imageTagChange(this)" style="width: 100%;"></textarea>'
					Dropzone.options['imagesDropZone' + nextImageArea + r + i] = {
						uploadMultiple : true,
						dictDefaultMessage : 'Drop Your Images Here',
		//				forceFallback : true,
						init : function dropzoneInit() {
							// body...
							this.on('successmultiple', function (addedFiles, b) {
								// body...
								setImagesInputs()
							})
						},
					}
				}
			}

			setImagesInputs()

			// APPLY THE DROPZONES
			Dropzone.discover()

			var elementName = 'image' + nextImageArea
			var layoutInputs = document.getElementById('layoutInputs')

			var elements = $('.elements')
			var nextElementIndex = elements.length / 2

			var layoutInputToAdd = 
		        '<input type="hidden" class="elements" name="elements[' + nextElementIndex + '][type]" value="imageArea">' +
		        '<input type="hidden" class="elements" name="elements[' + nextElementIndex + '][index]" value="' + nextImageArea + '">'
			layoutInputs.innerHTML += layoutInputToAdd

			// SET NEW VALUE FOR THE IMAGEZONE TRACKING VARIABLE
			$('#nextImageArea').val(++nextImageArea)


	  	}
	})

	$(".carriersButton").click(carriersButtonClick)
	$(".phoneNumbersButton").click(phoneNumbersButtonClick)
	$(".masterCheckBox").click(masterCheckBoxClick)
	$('.maillistCheckBox').click(maillistCheckBoxClick)
	$('#imagesLayoutButton').click(imagesLayoutButtonClick)
	$('#addTextButton').click(addTextButtonClick)
	$('#addImagesButton').click(addImagesButtonClick)

	$('#domainSelect').change(domainSelectChange)
	$('#messageSubject').change(messageSubjectChange)
	$('#messageText').change(messageTextChange)
	$('#numberOfColumns').change(numberOfColumnsChange)

	var maillistCheck = $('.maillistCheck')
	var maillistCheckBox = $('.maillistCheckBox')

	for (var i = 0; i < maillistCheck.length; i++) {
		if($(maillistCheck[i]).prop('value') == "on"){
			$(maillistCheckBox[i]).prop('checked', true)
		}
	}

	$.get('/initialWorkingDomain', 
		function initialWorkingDomainCallBack(data, status) {
			// body...
			if(data.status == 'OK'){
				$('#domainSelect').val(data.domainname)
			}
		})
});

function imageTagChange(element) {
	// body...
	element.innerHTML = $(element).val()
	setImagesInputs()
}

function sendclick() {
	// body...
	var to = $("#to").val();
	var message = $("#message").val();
	$.get("/sendEmail",
		{
			to: to,
			message: message,
		},
		function sendTextCallback(data, status) {
			// body...
			alert(data)
		}
	)
}

function verifyclick() {
	// body...
	var to = $("#to").val();
	var access_key = "eadc16f775c37e4161342657ba126dce";
	$.get("http://apilayer.net/api/validate",
		{
			access_key : access_key,
			number: to,
		},
		function verifyNumberCallBack(data, status) {
			// body...
			$("#message").val(data.carrier);
		}
	)
}

function carriersButtonClick() {
	// body...
}

function phoneNumbersButtonClick() {
	// body...
}

function uploadClick(argument) {
	// body...
	var fileName = $("#fileToUpload").val()
	$.get('/fileUpload', 
		{fileName : fileName},
		function fileUploadCallBack(data, status) {
			// body...
		})
}

function masterCheckBoxClick(argument) {
	// body...
	$('input.maillistCheckBox').prop('checked', $('.masterCheckBox').prop('checked'))
	verifyCheckedBoxes()
}

function maillistCheckBoxClick(argument) {
	// body...
	if($('input.maillistCheckBox:checked').length == $('input.maillistCheckBox').length){
		$('.masterCheckBox').prop('checked', true)
	}
	else{
		$('.masterCheckBox').prop('checked', false)
	}
	verifyCheckedBoxes()
}

function verifyCheckedBoxes() {
	// body...
	var checkedBoxes = $('input.maillistCheckBox:checked')
	$.each(checkedBoxes, function(index, element){
		var id = $(element).attr('id')
//		console.log($('#' + 'check_' + id))
		$('#' + 'check_' + id).val('on')
		$('#' + 'sendCheck_' + id).val('on')
		console.log($('#' + 'check_' + id))

//		console.log($('#' + 'check_' + id).val())
//		console.log($('#' + 'check_' + id))
	})

	var unCheckedBoxes = $('input.maillistCheckBox:not(:checked)')
	$.each(unCheckedBoxes, function(index, element){
		var id = $(element).attr('id')
		$('#' + 'check_' + id).val('off')
		$('#' + 'sendCheck_' + id).val('off')
	})
}

function sendMailToListClick() {
	// body...
	var mailingLists = []

	for (var i = $('input.maillistCheckBox:checked').length - 1; i >= 0; i--) {
		mailingLists.push($('input.maillistCheckBox:checked')[i].id)
	}
	var messageText = $('#messageText').val()
	var messageSubject = $('#messageSubject').val()
	var messageHtml = $('#htmlTextComponent').val()
	var images = []
	var pathRows = $(".pathRow")

	for (var i = 0; i < pathRows.length; i++) {
		var pathCols = $("#" + i).children()
		for (var j = 0; j < pathCols.length; j++) {
			var image = {path:$("#" + i +j).attr('src'), text:'text'}
			images.push(image)
		}
	}
	console.log(images)
	return
	$.get('/sendMailToLists',
		{
			to:mailingLists,
			messagetext:messageText,
			messagesubject:messageSubject,
			images:JSON.stringify(images)
//			messagehtml:messageHtml
		},
		function sendMailToListCallBack(data, status) {
		// body...
		console.log(data)
	})
}

function domainSelectChange() {
	// body...
	$.get('/setWorkingDomain', 
		{domainname:$('#domainSelect').val()},
		function SetWorkingDomainCallBack(data, status) {
			// body...
			console.log(data)
		})
}

function messageSubjectChange(argument) {
	// body...
	$('#messageSubjectUpload').val($('#messageSubject').val())
}

function messageTextChange(element) {
	// body...
	element.innerHTML = $(element).val()
	$('#' + ($(element)[0]).name).val($(element).val())
}

function imagesLayoutButtonClick(argument) {
	// body...
	$('#imagesLayoutButtonDiv').hide()

	$('#dialog').dialog("open")
}

function numberOfColumnsChange(argument) {
	// body...
	var numberOfColumns = $('#numberOfColumns').val()
	var maxColumns = $('#numberOfColumns').prop('max')
	var minColumns = $('#numberOfColumns').prop('min')
	if(numberOfColumns > maxColumns){
		$('#numberOfColumns').val(maxColumns)
	}
	if(numberOfColumns < minColumns){
		$('#numberOfColumns').val(minColumns)
	}
}

function setImagesInputs() {
	// body...

	var filesInputs = document.getElementById('filesInputs')
	filesInputs.innerHTML = ''

	var imagesTableIndex = 0
	$.each($('.imagesTable'), function dzimageTable (index, imagesTable) {
		var row = 0
		$.each(($(imagesTable)[0]).rows, function(index1, imagesTableRow){
			var col = 0
			$.each($(imagesTableRow).find('.dropzone'), function dropzoneForm(index, form) {
				// body...
				if($(form).find('.dz-image').length > 0){
					var imageIndex = 0
					$.each($(form).find('.dz-image'), function dzImages(index, dzimage) {
						// body...
						filesInputs.innerHTML += 
						'<input type="hidden" name="images[' + imagesTableIndex + '][' + row + '][' + col + '][' + imageIndex + '][path]" value="public/images/' + dzimage.children[0]['alt'] + '">'
						imageIndex++
					})
				}
				else
				{
					filesInputs.innerHTML += '<input type="hidden" name="images[' + imagesTableIndex + '][' + row + '][' + col + '][0][path]" value="public/images/blank.jpg">'
				}
				var tdElement = $(($(form)[0]).parentElement)
				var imageTag = tdElement.find('.imageTag')
				if(imageTag.length > 0){
					filesInputs.innerHTML += '<input type="hidden" name="images[' + imagesTableIndex + '][' + row + '][' + col + '][0][tag]" value="' + imageTag.val() + '">'
				}
				col++
			})
			row++
		})
		imagesTableIndex++
	})
}

function addTextButtonClick(argument) {
	// body...
	var nextTextArea = $('#nextTextArea').val()
	var elementName = 'message' + nextTextArea
	var layoutInputs = document.getElementById('layoutInputs')
	var elements = $('.elements')
	var nextElementIndex = elements.length / 2


	var layoutInputToAdd = 
        '<input type="hidden" class="elements" name="elements[' + nextElementIndex + '][type]" value="textArea">' +
        '<input type="hidden" id="' + elementName + '" class="elements" name="elements[' + nextElementIndex + '][text]" value="">'
	layoutInputs.innerHTML += layoutInputToAdd

	var layoutDiv = document.getElementById('layoutDiv')

//	console.log($('.textArea'))

	layoutDiv.innerHTML += 
        '<div class="messageComponent messageTextDiv">' +
    		'<label>TEXT:</label>' + 
    		'<textarea type="text" onchange="messageTextChange(this)" name="' + elementName + '" class="messageComponent messageTextComponent textArea"></textarea>' +
		'</div>'

	$('#nextTextArea').val(++nextTextArea)

//	console.log($('.textArea'))
}

function addImagesButtonClick(argument) {
	// body...
	$( "#dialog" ).dialog({
	  position: { my: "right bottom", at: "right top", of: $("#addImagesButton") }
	});

	$('#dialog').dialog("open")
}

function ipcheck() {
	// GET THE LOCATION OF THE SITE VISITOR

	// WE FIRST GET THE IP API LOCATOR ACCESS_KEY 
	// THAT IS STORED IN THE ENV ARRAY
	$.get('/ipLocationKey', function(access_key){
		// NOW WE REQUEST THE LOCATION TO THE API
		// WE DO IT FROM HERE, THE FRONT END, AS WE WANT THE LOCATION 
		// OF THE SITE VISITOR. IF WE DO IT FROM THE BACK END WE WILL
		// GET THE WEB SITE HOST IP LOCATION.
		$.post('http://api.ipstack.com/check?access_key=' + access_key, 
			{dataType:'jsonp'},
			function(data, status){
				// THE API PROVIDES US WITH AN IMAGE OF THE LOCATION COUNTRY FLAG 
				($('#flag')[0]).src = data.location.country_flag;
				// NOW WE SHOW THE CITY AND COUNTRY
				($('#ipLocation')[0]).textContent = 'Your Location Is: ' + data.city + ', ' + data.region_name + ', ' + data.country_name;
		})
	})
}

function zonesCheck(argument) {
	// body...
	$.get('/zonesCheck', function(data, status){
		console.log(data)
	})
}