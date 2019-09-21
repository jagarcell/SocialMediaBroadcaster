@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<title>CREATE MAIL LIST</title>

	@section('scripts')
	<!-- Scripts -->
    <script type="text/javascript" src="/public/js/jquery-1.7.2.min.js"></script> 
	<script type="text/javascript" src="/public/js/createmaillist.js"></script>
	@endsection

	@section('styles')
	<!-- Styles -->
	<link rel="stylesheet" type="text/css" href="/public/css/createmaillist.css">
	@endsection
</head>
<body>
	@section('bodymaindiv')
	<form action="/addMailingList">
		<div>
			<div class="addMailingListLabel">
				Add Mailing List
			</div>
			<div class="addMailingListResultMessage" id="addMailingListResultMessage">
				
			</div>
			<div class="newListFieldLabel">
				Alias Address:
			</div>
			<div class="newListField">
				<input type="text" name="address" class="newListFieldInput" id="address">
			</div>
			<div class="arrobaLabel">@</div>
			<div class="newListDomainField">
				<select name="domain" id="domain">
					@foreach($domains as $key => $domain)
					<option value="{{$domain->name}}">{{$domain->name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div>
			<div class="newListFieldLabel">
				Name:
			</div>
			<div class="newListField">
				<input type="text" name="name" class="newListFieldInput" id="name">
			</div>
		</div>
		<div>
			<div class="newListFieldLabel">
				Description:
			</div>
			<div class="newListField">
				<input type="text" name="description" class="newListFieldInput" id="description">
			</div>
		</div>
		<div>
			<div class="newListFieldLabel">
				Access Level:
			</div>
			<div class="newListField">
				<select name="access_level" id="access_level">
					<option value="readonly">Read Only</option>
					<option value="members">Members</option>
					<option value="everyone">Everyone</option>
				</select>
			</div>
		</div>
		<div class="addMailingListLabel">
			<input type="button" value="Add Mailing List" class="addMailingListButtonClass">
		</div>
	</form>
	@endsection
</body>
</html>