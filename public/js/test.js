$(document).ready(function () {
	// body...

	// Dialog Test
	$( "#dialog" ).dialog({ autoOpen: false });
	$( "#dialog" ).dialog({
	  position: { my: "left top", at: "right bottom", of: $("#opener") }
	});

	$( "#opener" ).click(openerClick)

	// Mailgun Email Send Test
	$('.sendEmailButton').click(sendEmailButtonClick)

	// Images Drag And Drop Test
	$('#holder').on({
	    'dragover dragenter': function(e) {
	        e.preventDefault();
	        e.stopPropagation();
	    },
	    'drop': function(e) {
	        //console.log(e.originalEvent instanceof DragEvent);
	        var dataTransfer =  e.originalEvent.dataTransfer;
	        if( dataTransfer && dataTransfer.files.length) {
	            e.preventDefault();
	            e.stopPropagation();
	            $.each( dataTransfer.files, function(i, file) { 
	              var reader = new FileReader();
	              reader.onload = $.proxy(function(file, $fileList, event) {
	                var img = file.type.match('image.*') ? "<img class='droppedImage' src='" + event.target.result + "' /> " : "";
	                $fileList.prepend( $("<li>").append( img + file.name ) );
	              }, this, file, $("#fileList"));
	              reader.readAsDataURL(file);
	            });
	        }
	    }
	})

	$('#quickBooksButton').click(clickQuickBooksButton)
	$('#flushSession').click(flushSessionClick)
	$('#companyInfo').click(companyInfoClick)
	$('#inventorySummary').click(inventorySummaryClick)
})

function openerClick() {
	// body...
	$('#dialog').dialog("open")
}

function sendEmailButtonClick() {
	// body...
	var images = $(".droppedImage")
	var formData = new FormData();
	for (var i = 0; i < images.length; i++) {
		formData.append('files[]', images[i])
	}
	
	$.get('/sendTestEmail', 
		{data : formData},
		function sendTestEmailCallBack(data, status) {
		// body...
		console.log(data)
	})
}

function clickQuickBooksButton() {
	// body...
	$.get('/quickBooks', function quickBooksCallBack(data, status) {
		// body...
		if(data.authUrl == null){
			console.log(data)
		}
		else{
			window.open(data.authUrl, '_parent', 'left=300, top=40, width=200, height=200')
		}
	})
}

function flushSessionClick() {
	// body...
	$.get('/flushSession', function flushSessionCallBack(data, status) {
		// body...
		window.open('/test', '_parent')
	})
}

function companyInfoClick() {
	// body...
	$.get('/companyInfo', function companyInfoCallBack(data, status) {
		// body...
		if(data.authUrl != null){
			window.open(data.authUrl, '_parent', 'left=300, top=40, width=200, height=200')
		}
		else{
			$('#companyInfoLabel')[0].textContent = data
		}
	})
}

function inventorySummaryClick() {
	// body...
	$.get('/inventorySummary', function inventorySummaryCallBack(data, status) {
		// body...
		if(data.authUrl != null){
			window.open(data.authUrl, '_parent', 'left=300, top=40, width=200, height=200')
		}
		else{
			$('#companyInfoLabel')[0].textContent = data
		}
	})
}