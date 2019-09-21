<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- scripts -->
        <script type="text/javascript" src="/public/js/jquery-1.7.2.min.js"></script> 
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="/public/css/admin.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="mainDiv">

        <!-- CARRIERS TABLE -->
        <table class="carriersTable" id="carriersTableId">

            <!-- TABLE HEADER -->
            <tr">
                <th class="dataColumn">Name</th>
                <th class="dataColumn">Gateway</th>
                <th class="dataColumn">Alias</th>
                <th class="buttonColumn">
                    <input type="button" class="buttonRight" name="addCarrierButton" value="+" title="ADD A CARRIER" onclick="addCarrierButtonClick()">
                </th>
                <th class="buttonColumn">
                    <input type="button" class="buttonRight" name="deleteSelectedCarriersButton" value="-" title="DELETE SELECTED CARRIERS" onclick="deleteSelectedCarriers()">
                </th>
            </tr>
            <!-- CREATE A LIST OF THE CARRIERS ALREADY PRESENT IN OUR DATABASE -->
            @foreach($carriers as $key => $carrier)
            <tr>
                <!-- THESE TDS ARE FOR SHOWING THE CARRIERS ALREADY REGISTERED IN THE DATABASE -->

                <td class="dataColumn {{$carrier->name}}_show" id="{{$carrier->name}}_name_value">{{$carrier->name}}</td>
                <td class="dataColumn {{$carrier->name}}_show" id="{{$carrier->name}}_gateway_value">{{$carrier->gateway}}</td>
                <td class="dataColumn {{$carrier->name}}_show" id="{{$carrier->name}}_alias_value">{{$carrier->alias}}</td>

                <td class="buttonColumn {{$carrier->name}}_show">
                    <input type="button" name="" value=" Edit " class="buttonRight" title="TO CHANGE THE CONTENT OF THIS ROW" onclick="editButtonClick('{{$carrier->name}}')">
                </td>
                <td class="buttonColumn {{$carrier->name}}_show">
                    <input type="checkbox" name="editButton" class="buttonRight">
                </td>

                <!-- THESE TDS ARE FOR EDITING AN EXISTING CARRIER -->
                <!-- TO BE SHOWN WHEN EDIT BUTTONIS CLICKED --> 
                <td class="dataColumn {{$carrier->name}}_edit carrierInput ">
                    <input type="text" class="inputNoBorder {{$carrier->name}}_name_edit_value">
                </td>
                <td class="dataColumn {{$carrier->name}}_edit carrierInput">
                    <input type="text" class="inputNoBorder {{$carrier->name}}_gateway_edit_value">
                </td>
                <td class="dataColumn {{$carrier->name}}_edit carrierInput">
                    <input type="text" class="inputNoBorder {{$carrier->name}}_alias_edit_value">
                </td>
                <td class="buttonColumn {{$carrier->name}}_edit carrierInput">
                    <input type="button" class="buttonRight {{$carrier->name}}_edit" value="Update" title="TO UPDATE THE CHANGES MADE" onclick="updateButtonClick('{{$carrier->name}}')">
                </td>
            </tr>
            @endforeach

            <!-- THIS IS THE ROW USED TO ADD A NEW CARRIER TO THE LIST -->
            <!-- TO BE SHOWN WHEN THE + (NEW CARRIER) BUTTON IS CLICKED -->
            <tr class="new_Carrier">
                <td class="dataColumn">
                    <input type="text" class="inputNoBorder {{$carrier->name}}_name_new_value">
                </td>
                <td class="dataColumn">
                    <input type="text" class="inputNoBorder {{$carrier->name}}_gateway_new_value">
                </td>
                <td class="dataColumn">
                    <input type="text" class="inputNoBorder {{$carrier->name}}_alias_new_value">
                </td>
                <td class="buttonColumn">
                    <input type="button" class="{{$carrier->name}}_new buttonRight" value=" Add " onclick="addNewButtonClick('{{$carrier->name}}')">
                </td>
            </tr>
        </table>
        
        <script type="text/javascript" src="/public/js/admin.js"></script>
        </div>
    </body>
</html>
