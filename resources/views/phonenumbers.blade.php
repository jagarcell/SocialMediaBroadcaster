@extends('layouts.app')
<!DOCTYPE html>
<html>
	<head>
		<title>Phone Numbers Edition</title>

		@section('scripts')
	    <!-- scripts -->
		<script src="//code.jquery.com/jquery-1.12.4.js"></script>
		<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	    <script type="text/javascript" src="/public/js/phonenumbers.js"></script>
	    @endsection

	    @section('fonts')
	    <!-- Fonts -->
	    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
	    @endsection

	    @section('styles')
	    <!-- Styles -->
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	    <link rel="stylesheet" type="text/css" href="/public/css/phonenumbers.css">
	    @endsection
	</head>
	<body onunload="pageUnload()">
		@section('bodymaindiv')
		<div class="phoneNumbersMainDiv">
			<table id="phoneNumbersTable" class="phoneNumbersTable">
				<th>PHONE NUMBER</th>
				<th>CARRIER</th>
				<th>GROUP</th>
				<th>SUSCRIBER</th>
				<th><input type="button" class="deleteButton" value="DELETE"></th>
				<th><input type="button" class="updateButton" value="UPDATE"></th>
				@foreach($phoneNumbers as $key => $phoneNumber)
				<tr>
					<td>
						<input type="text" id="phoneNumberInput_{{$phoneNumber->id}}" class="tableInput" value="{{$phoneNumber->phonenumber}}" onchange="phoneNumberInputChange({{$phoneNumber->id}})">
					</td>
<!--					
					<td>
						<input type="text" id="carrierInput_{{$phoneNumber->id}}" class="tableInput" value="{{$phoneNumber->carrier}}" onchange="carrierInputChange({{$phoneNumber->id}})">
					</td>
-->
					<td>
						<select id="carrierInput_{{$phoneNumber->id}}" class="tableInput" onchange="carrierInputChange({{$phoneNumber->id}})">
							@foreach($carriers as $key => $carrier)
							@if($carrier->alias == $phoneNumber->carrier)
							<option value="{{$carrier->alias}}" selected="">{{$carrier->alias}}</option>
							@else
							<option value="{{$carrier->alias}}">{{$carrier->alias}}</option>
							@endif
							@endforeach
						</select>
					</td>

					<td>
						<input type="text" id="groupInput_{{$phoneNumber->id}}" class="tableInput" value="{{$phoneNumber->group}}" onchange="groupInputChange({{$phoneNumber->id}})">
					</td>
					<td>
						<input type="text" id="suscriberInput_{{$phoneNumber->id}}" class="tableInput" value="{{$phoneNumber->suscriber}}" onchange="suscriberInputChange({{$phoneNumber->id}})">
					</td>
					<td>
						<input type="checkbox" id="{{$phoneNumber->id}}" class="deleteCheckbox deleteCheckbox_{{$phoneNumber->id}}">
					</td>
					<td>
						<input type="checkbox" id="{{$phoneNumber->id}}" class="updateCheckbox updateCheckbox_{{$phoneNumber->id}}">
					</td>
				</tr>
				@endforeach
			</table>
		</div>
		<div class="firstLinkDiv paginationLinkDiv"><a href="{{$phoneNumbers->url(1)}}">First</a></div>
		<div class="previousLinkDiv paginationLinkDiv"><a href="{{$phoneNumbers->previousPageUrl()}}" ><< Previous</a></div>
		<div class="nextLinkDiv paginationLinkDiv"><a href="{{$phoneNumbers->nextPageUrl()}}">Next >></a></div>
		<div class="lastLinkDiv paginationLinkDiv"><a href="{{$phoneNumbers->url($phoneNumbers->lastPage())}}">Last</a></div>
		<div class="addNewButtonDiv">
			<!--
			<input class="addNewButton" value="ADD NEW PHONE NUMBER" type="button" title="ADD A NEW PHONE NUMBER" onclick="addNewButtonClick('{{$phoneNumbers->url($phoneNumbers->lastPage())}}')">
			-->


			<button id="opener" class="addNewButton">Add New Phone Number</button>
			<button id="propagateGroup" class="groupPropagation">Change Selected Groups</button>
			<div id="dialog" title="Enter Phone Data Here" class="newPhoneDialog">
					<div class="newPhoneInput">
						<label>Phone Number:</label>
						<input type="text" id="newPhoneNumberInput" class="tableInput" value="">
					</div>
					<div class="newPhoneInput">
						<label>Carrier:</label>
						<select id="newCarrierInput" class="tableInput">
							@foreach($carriers as $key => $carrier)
							@if($carrier->alias == $phoneNumber->carrier)
							<option value="{{$carrier->alias}}" selected="">{{$carrier->alias}}</option>
							@else
							<option value="{{$carrier->alias}}">{{$carrier->alias}}</option>
							@endif
							@endforeach
						</select>
					</div>

					<div class="newPhoneInput">
						<label>Group:</label>
						<input type="text" id="newGroupInput" class="tableInput" value="">
					</div>
					<div class="newPhoneInput">
						<label>Suscriber:</label>
						<input type="text" id="newSuscriberInput" class="tableInput" value="">
					</div>
			</div>
			<div id="propagateGroupDialog" title="Enter A Group Name">
				<div class="newPhoneInput">
					<label>Group Name:</label>
					<input type="text" id="propGroupName" class="tableInput">
				</div>
			</div>
		</div>
		@endsection
	</body>
</html>