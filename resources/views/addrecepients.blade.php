@extends('layouts.app')
<!DOCTYPE html>
<html>
<head>
	<title>Add Recepient</title>

	@section('scripts')
	<!-- Scripts -->
	<!--
    <script type="text/javascript" src="/public/js/jquery-1.7.2.min.js"></script>
    --> 
	<script src="//code.jquery.com/jquery-1.12.4.js"></script>
	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="/public/js/addrecepient.js"></script>
	@endsection
	
	@section('styles')  
	<!-- Styles -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/public/css/addrecepient.css">
	<link rel="stylesheet" type="text/css" href="/public/css/app.css">
	@endsection
</head>
<body>
	@section('bodymaindiv')
	<div class="mainDiv">
		<div class="lm mailinglist labelcolor">
			MAILING LIST
		</div>
		<div id="address" class="lm listname namecolor">
			{{$address}}
		</div>

		<div class="bigverticalseparation labelcolor lm">Recepients That Can Be Added To The List</div>
		<div class="smallverticalseparation">
			<label class="lm">Filter:</label>
			<div class="lm filteroption">
				<label>Phone Number:</label>
				<select id="phonefilter">
					<option value="All">All</option>				
					<option value="Starting">Starting By</option>
				</select>
				<input type="text" id="phonestarting" class="smallverticalseparation">
			</div>
			<div class="lm smallverticalseparation filteroption">
				<label>Carrier:</label>
				<select id="carrier">
					<option value="All">All</option>
					@foreach($carriers as $key => $carrier)
						<option value="{{$carrier->alias}}">{{$carrier->alias}}</option>
					@endforeach  
				</select>
				
			</div>
			<div class="lm smallverticalseparation filteroption">
				<input type="button" class="showRecepients" value="Update Recepients View">
				<input type="button" id="addRecepients" class="actionButton" value="Add Seclected Recepients">
			</div>
		</div>
		
		<div class="verifiednumbers smallverticalseparation lm">
			<table id="recepientsTable">
				<th><input type="checkbox" class="masterCheckBox"></th>
				<th>Phone Number</th>
				<th>Carrier</th>
				@foreach($verifiednumbers as $key => $verifiednumber)
				<tr class="recepientRow" id="{{$verifiednumber->phonenumber}}">
					<td><input type="checkbox" class="addRecepientsCheckBox"></td>
					<td id="td_phonenumber_{{$verifiednumber->phonenumber}}">{{$verifiednumber->phonenumber}}</td>
					<td id="td_carrier_{{$verifiednumber->phonenumber}}">{{$verifiednumber->carrier}}</td>
				</tr>
				@endforeach
			</table>
		</div>
	
		</div>
	</div>
	@endsection
</body>
</html>


