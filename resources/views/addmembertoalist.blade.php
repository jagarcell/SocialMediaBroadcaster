@extends('layouts.app')
<!DOCTYPE html>
<html>
<head>
	<title>ADD MEMBER TO A LIST</title>

	@section('scripts')
	<!-- Scripts -->
	<!--
    <script type="text/javascript" src="/public/js/jquery-1.7.2.min.js"></script>
    --> 
	<script src="//code.jquery.com/jquery-1.12.4.js"></script>
	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="/public/js/addmembertoalist.js"></script>
	@endsection
	
	@section('styles')  
	<!-- Styles -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/public/css/addmembertoalist.css">
	@endsection
</head>
<body>
	@section('bodymaindiv')
	<div class="mainDiv">
		<div class="mailingListLabel"><label>Mailing List</label></div>
		<div class="mailingList"><label id="address">{{$address}}</label></div>
		<div class="addMailingListResultMessage"></div>
		<div class="details"><label>Details</label></div>
		<div class="mailingListFieldDiv">
			<div class="fieldLabel"><label>Alias Address</label></div>
			<div class="memberField"><label>{{$address}}</label></div>
		</div>
		<div class="mailingListFieldDiv">
			<div class="fieldLabel"><label>Name</label></div>
			<div class="memberField"><input type="text" name="" class="memberFieldInput" id="name" value="{{$name}}"}></div>
		</div>
		<div class="mailingListFieldDiv">
			<div class="fieldLabel"><label>Description</label></div>
			<div class="memberField"><input type="text" name="" class="memberFieldInput" id="description" value="{{$description}}"}></div>
		</div>
		<div class="mailingListFieldDiv">
			<div class="fieldLabel"><label>Access Level</label></div>
			<div class="memberField">
				<select  id="{{$access_level}}" class="access_level">
					<option value="readonly">Read Only</option>
					<option value="members">Members</option>
					<option value="everyone">Everyone</option>
				</select>
			</div>
		</div>
		<div class="details"><label>Recepients</label></div>
		<div class="addRecepientDiv">
			<form action="/addRecepients">
				<input type="text" name="address" value="{{$address}}" hidden="">	
				<input type="submit" id="addRecepientButton" value="Add Recepient" class="recepientActionButtonClass">
			</form>
			<button class="recepientActionButtonClass" id="recepientDeleteButton">Remove Recepient</button>
		</div>
		<div class="membersList">
			<table>
				<th><input type="checkbox" id="mastercheckbox"></th>
				<th>Recepient Address</th>
				@foreach($members->items as $key => $item)
				<tr id="{{$item->address}}">
					<td><input type="checkbox" class="recepientcheckbox" value="{{$item->address}}"></td>
					<td id="{{$item->address}}_td">{{$item->address}}</td>
				</tr>
				@endforeach
			</table>
		</div>
	</div>
	@endsection
</body>
