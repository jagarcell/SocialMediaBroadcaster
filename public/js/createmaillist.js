$(document).ready(function() {
	// body...
	$('.addMailingListButtonClass').click(addMailingListButtonClick)
})

function addMailingListButtonClick($request) {
	// body...
	var address = $('#address').val()
	var domain = $('#domain').val()
	var name = $('#name').val()
	var description = $('#description').val()
	var access_level = $('#access_level').val()

	$.get('/addMailingList',
		{
			'address': address,
			'domain' : domain,
			'name' : name,
			'description' : description,
			'access_level' : access_level
		},
		function addMailingListCallBack(data, status) {
			// body...
			alert('addMailingListCallBack')
			var resultMessage = document.getElementById('addMailingListResultMessage')

			var dataJson = JSON.parse(data)
			if(dataJson.status == 'OK'){
				window.location.replace('/addMemberToAList?address=' + 
					dataJson.address +
					'&name=' + dataJson.name +
					'&description=' + dataJson.description +
					'&access_level=' + dataJson.access_level +
					'&mensaje=' + 'Info Successfully created mailing list')
			}
			else{
				resultMessage.style.color = 'red'
				resultMessage.innerHTML = 'Error: Failed to create the mailing lists. Please try again in a moment.'
			}
		}
	)
}