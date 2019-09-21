@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<title>Mailing Lists</title>

	@section('scripts')
	<!-- Scripts -->
    <script type="text/javascript" src="/public/js/jquery-1.7.2.min.js"></script> 
	<script type="text/javascript" src="/public/js/mailinglists.js"></script>
	@endsection
	
	@section('styles')
	<!-- Styles -->
	<link rel="stylesheet" type="text/css" href="/public/css/mailinglists.css">
	@endsection
</head>
<body>
	@section('bodymaindiv')
	<div class="mailingListMainDiv">  
		<div class="actionsDiv">
			<input type="button" id="createListButton" class="createListButton listButton" value="Create Mailing List" id="createListButton" onclick="window.location.href='/createMailList'">
			<input type="button" id="deleteListButton" class="deleteListButton listButton" value="Delete Mailing List">
		</div>
		<div class="mailingListMainDiv">
			<table class="mailinglistsTable" id="mailinglistsTable">
				<tr>
					<th><input type="checkbox" class="masterCheck"></th>
					<th>NAME</th>
					<th>ADDRESS</th>
					<th>DESCRIPTION</th>
					<th>ACCESS LEVEL</th>
					<th>CREATED AT</th>
					<th>MEMBERS COUNT</th>
				</tr>
				@foreach($items as $key => $item)
				<tr id="{{$item->address}}">
					<td><input type="checkbox" class="deleteCheckBox"></td>
					<td>
						<a href='/addMemberToAList?name={{$item->name}}&address={{$item->address}}&description={{$item->description}}&access_level={{$item->access_level}}&result={{$item->access_level}}'>{{$item->name}}</a>
					</td>
					<td class="itemAddress">{{$item->address}}</td>
					<td>{{$item->description}}</td>
					<td>{{$item->access_level}}</td>
					<td>{{$item->created_at}}</td>
					<td>{{$item->members_count}}</td>
				</tr>
				@endforeach
				<!--
				<tr>
					<td><input type="text" class="newListNameInput" id="newListName"></td>
					<td><input type="text" class="newListAddressInput" id="newListAddress"></td>
					<td><input type="text" class="newListDescriptionInput" id="newListDescription"></td>
					<td></td>
					<td><input type="button" value="ADD NEW LIST" title="ADD A MAILING LIST" class="addMailingListButton"></td>
				</tr>
				-->
		</table>
		</div>
	</div>
	@endsection
</body>
</html>