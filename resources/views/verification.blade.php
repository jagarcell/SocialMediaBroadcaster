@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<title>Phone Numbers Verification</title>

	@section('scripts')
    <!-- scripts -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.12.4.js"></script>
  	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="/public/js/verification.js"></script>
    @endsection

    @section('fonts')
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    @endsection

    @section('styles')
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="/public/css/verification.css">
    @endsection

</head>
<body>
	@section('bodymaindiv')
	<div class="numbersToVerifyDiv">
		<div class="phoneListLabel listLabel"><h3>PHONE NUMBERS TO BE VERIFIED</h3></div>
		<table class="numbersToVerifyTable" id="numbersToVerifyTable">
			<th class="phoneColumn">Phone Number</th>
			<th class="statusColumn">Status</th>
			<th class="checkButtonColumn"><input type="button" name="" title="DELETE SELECTED" value="DELETE" class="deleteButtonClick squareButton"></th>
			<th class="checkButtonColumn"><input type="button" name="" title="UPDATE SELECTED" value="UPDATE" class="updateButtonClick squareButton"></th>
			@foreach($numberstoverify as $key => $numbertoverify)
			<tr id="tr_{{$numbertoverify->id}}">
				<td id="phoneColumn_{{$numbertoverify->id}}" class="phoneColumn"><input class="phoneInput" type="" name="" value="{{$numbertoverify->phonenumber}}" id="phoneInputChangedCheckbox_{{$numbertoverify->id}}" onchange="phoneInputChange('phoneInputChangedCheckbox_{{$numbertoverify->id}}')"></td>
				<td id="statusColumn_{{$numbertoverify->id}}" class="statusColumn statusCell {{$numbertoverify->status}}">{{$numbertoverify->status}}</td>
				<td class="checkButtonColumn"><input type="checkbox" id="{{$numbertoverify->id}}" class="deleteCheckBox deleteCheckBox_{{$numbertoverify->id}}"></td>
				<td class="checkButtonColumn"><input type="checkbox" id="{{$numbertoverify->id}}" class="updateCheckBox phoneInputChangedCheckbox_{{$numbertoverify->id}}"></td>
			</tr>
			@endforeach
			<tr>
				<td class="phoneColumn"><input class="phoneInput phoneToAdd" id="phoneInput" type="" name=""></td>
				<td class="statusColumn" colspan="3"><input type="button" name="" class="addButton squareButton" value="<<<  ADD TO VERIFY"></td>
			</tr>
		</table>
		<input class="populateWithSecuenceButton roundedButton" type="button" name="" value="POPULATE WITH A SECUENCE OF 10 NUMBERS" title="CLICK HERE TO POPULATE THE LIST WITH A SECUENCE OF 10 NUMBERS STARTING AFTER THE LAST SHOWN">
		<input class="verifyPendingNumbersButton roundedButton" type="button" name="" value="VERIFY PENDING NUMBERS" title="VERIFY THE  CARRIERS OF THE PHONE NUMBERS ABOVE">
	</div>
	<div class="verifiedNumbersDiv">
		<div class="phoneListLabel listLabel"><h3>NUMBERS VERIFICATION RESULTS</h3></div>
		<table class="verifiedNumbersTable" id="verifiedNumbersTable">
			<th class="phoneVerifiedColumn">Phone Number</th>
			<th class="carrierVerifiedColumn">Carrier</th>
			<th class="groupVerifiedColumn">Group</th>
			<th class="gatewayVerifiedColumn">Gateway</th>
			<!--
			<tr>
				<td class="phoneVerifiedColumn"></td>
				<td class="carrierVerifiedColumn"></td>
				<td class="groupVerifiedColumn"><input type="text" class="groupInput" value="DEFAULT"></td>
				<td class="gatewayVerifiedColumn"><input type="text" class="gatewayInput" value="NEW" onchange="gatewayInputChange"></td>
			</tr>
			-->
			<tr>
				<td colspan="4"><input class="saveResultsButton squareButton" type="button" title="SAVE THE VERIFICATION RESULTS" value="SAVE ALL RESULTS"></td>
			</tr>
		</table>
		<div class="changeGroupButtonDiv">
			<button class="changeGroupButton roundedButton">CHANGE GROUPS</button>
			<div id="groupChangeDialog" title="Enter A Group Name" class="changeGroupButtonDiv">
				<input type="text" id="groupNameToChange" class="groupNameToChange">
				<input type="button" name="" class="changeGroupAcceptButton roundedButton" value="ACCEPT">
			</div>
		</div>
	</div>
	@endsection
</body>
</html>
