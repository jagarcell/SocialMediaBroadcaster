@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<title>Carriers</title>

	@section('scripts')
	<!-- Scripts -->
    <script type="text/javascript" src="/public/js/jquery-1.7.2.min.js"></script> 
	<script type="text/javascript" src="/public/js/carriers.js"></script>
	@endsection

	@section('styles')
	<!-- Styles -->
	<link rel="stylesheet" type="text/css" href="/public/css/carriers.css">
	@endsection
</head>
<body>
	@section('bodymaindiv')
	<div class="carriersMainDiv">
		<table class="carriersTable" id="carriersTable">
			<th>CARRIER</th>
			<th>SMS GATEWAY</th>
			<th>ALIAS</th>
			<th class="deleteButtonColumn"><input type="button" value="DELETE" title="DELETE THE SELECTED CARRIERS" class="deleteButton squareButton"></th>
			<th class="updateButtonColumn"><input type="button" value="UPDATE" title="UPDATE THE SELECTED CARRIERS" class="updateButton squareButton"></th>
			@foreach($carriers as $key => $carrier)
			<tr id="tr_{{$carrier->id}}">
				<td><input type="text" id="carrierInput_{{$carrier->id}}" class="carrierInput" value="{{$carrier->name}}" onchange="carrierInputChange({{$carrier->id}})"></td>
				<td><input type="text" id="gatewayInput_{{$carrier->id}}" class="gatewayInput" value="{{$carrier->gateway}}" onchange="carrierInputChange({{$carrier->id}})"></td>
				<td><input type="text" id="aliasInput_{{$carrier->id}}" class="aliasInput" value="{{$carrier->alias}}" onchange="carrierInputChange({{$carrier->id}})"></td>
				<td class="deleteButtonColumn"><input type="checkbox" id="{{$carrier->id}}" class="deleteCheckBox deleteCheckBox_{{$carrier->id}}"></td>
				<td class="updateButtonColumn"><input type="checkbox" id="{{$carrier->id}}" class="updateCheckBox updateCheckBox_{{$carrier->id}}"></td>
			</tr>
			@endforeach
			<tr>
				<td><input type="text" class="carrierInput" id="newCarrier"></td>
				<td><input type="text" class="gatewayInput" id="newGateway"></td>
				<td><input type="text" class="aliasInput" id="newAlias"></td>
				<td colspan="2"><input type="button" value="ADD CARRIER" title="ADD A CARRIER" class="addCarrierButton squareButton"></td>
			</tr>
		</table>
	</div>
	@endsection
</body>
</html>