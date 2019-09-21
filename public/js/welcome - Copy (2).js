$(document).ready(function() {
	// body...
	$( "#dialog" ).dialog({ autoOpen: false });
	$( "#dialog" ).dialog({
	  position: { my: "right top", at: "right bottom", of: $("#messageToListDiv") }
	});
	$( "#dialog" ).dialog({
		close: function( event, ui ) {
		// THE CODE TO PROCCESS THE DIALOG RESULTS GOES HERE
			var imagesTableZone = $('#imagesTableZone')
			var numberOfColumns = $('#numberOfColumns').val()

			var row = ($('#imagesTableZone')[0]).insertRow(0)
			for (var i = 0; i < numberOfColumns; i++) {
				var td = row.insertCell(-1)
				td.innerHTML = 
				'<form action="/fileUpload" method="post" enctype="multipart/form-data"class="dropzone uploadDiv" id="imagesDropZone' + i +'"></form>'

				Dropzone.options['imagesDropZone' + i] = {
				uploadMultiple : true,
				dictDefaultMessage : 'Drop Your Images Here',
//				forceFallback : true,
				init : function dropzoneInit() {
					// body...
					this.on('successmultiple', function (addedFiles, b) {
						// body...
						var imagesTable = document.getElementById('imagesTable')
						var rows = ($(imagesTable)[0]).rows
						var mergedRows = []
						var k = 0;
						for (var i = 0; i < rows.length; i++) {
							mergedRows.push(rows[i])
							k++
						}
						for (var i = 0; i < addedFiles.length; i++) {
							var ik = i + k
							var newRow = imagesTable.insertRow(0)
							newRow.id = ik

							newRow.innerHTML = 
								'<td>' +
									'<div>' +
										'<input type="hidden" name="images[]" value="public/images/' + addedFiles[i].name + '">' +
									'</div>' +
								'</td>'
						}
					})
				},
				}
			}
			Dropzone.discover()
	  	}
	})

/*
	Dropzone.options.imagesDropZone = {
		uploadMultiple : true,
		dictDefaultMessage : 'Drop Your Images Here',
//		forceFallback : true,
		init : function dropzoneInit() {
			// body...
			this.on('successmultiple', function (addedFiles, b) {
				// body...
				var imagesTable = document.getElementById('imagesTable')
				var rows = ($(imagesTable)[0]).rows
				var mergedRows = []
				var k = 0;
				for (var i = 0; i < rows.length; i++) {
					mergedRows.push(rows[i])
					k++
				}
				for (var i = 0; i < addedFiles.length; i++) {
					var ik = i + k
					var newRow = imagesTable.insertRow(0)
					newRow.id = ik

					newRow.innerHTML = 
						'<td>' +
							'<div>' +
								'<input type="hidden" name="images[]" value="public/images/' + addedFiles[i].name + '">' +
							'</div>' +
						'</td>'
				}
			})
		},
	}
*/

	$(".carriersButton").click(carriersButtonClick)
	$(".phoneNumbersButton").click(phoneNumbersButtonClick)
	$(".masterCheckBox").click(masterCheckBoxClick)
	$('.maillistCheckBox').click(maillistCheckBoxClick)
	$('#domainSelect').change(domainSelectChange)

	$('#messageSubject').change(messageSubjectChange)
	$('#messageText').change(messageTextChange)
	$('#imagesLayoutButton').click(imagesLayoutButtonClick)
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

function readfiles(files) {
	alert('readfiles')
	var i = 0

	var formData = new FormData(); // we initialise a new form that will be send to php 

	for (var i = 0; i < files.length; i++) {  // if we have more that one file
//	    previewImg(files[i]); // function to preview images

		formData.append('file'+ i, files[i])
	}

	console.log(formData)
/*
	$.get('/fileUpload',
        {image:formData,},
	    function fileUploadCallBack (data, status) {
	        console.log(data)
	    }
 	)
*/
	$.ajax({
	  url: '/fileUpload',
	  contentType: false,
	  cache: false,
	  processData: false,
	  type: 'POST',
	  data: 'fff'
	});	
}       

function previewImg(file) {
	alert('previewImg')
	return;
	var reader = new FileReader();

	reader.onload = function (event) {

	     var image = new Image();

	    image.src = event.target.result; // set image source

	    image.width = 550; // a fake resize


	    document.getElementById('message').appendChild(image); // append image to body

	}

	reader.readAsDataURL(file);
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
	alert('uploadClick')
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
//			console.log(data)
		})
}

function messageSubjectChange(argument) {
	// body...
	$('#messageSubjectUpload').val($('#messageSubject').val())
}

function messageTextChange(argument) {
	// body...
	$('#messageTextUpload').val($('#messageText').val())
}

function imagesLayoutButtonClick(argument) {
	// body...
	$('#imagesLayoutButton').hide()

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