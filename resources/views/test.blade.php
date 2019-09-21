<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>dialog demo</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" type="text/css" href="/public/css/test.css">

  <script src="//code.jquery.com/jquery-1.12.4.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="/public/js/test.js"></script>
  <script type="text/javascript" src="/public/js/dropzone.js"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/public/css/dropzone.css">
  <!--
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  -->
</head>
<body>
  <div class="">
    <button id="opener"class="btn btn-default btn-sm leftTestDiv">open the dialog<span class="glyphicon glyphicon-pencil"></span></button>
    <div id="dialog" title="Dialog Title" class="leftTestDiv">
    	<div>
    		I'm a dialog
    	</div>
    	<div>
    		<input type="text" name="" value="INPUT">
    	</div>
    </div>
    <div class="topSeparation leftTestDiv">
      <input type="button" class="sendEmailButton" value="Send Test Email Using Mailgun">  
    </div>
    <!--
    <div>
      <table id="holder">
        <tr>
          <td>Drop files here</td>
        </tr>
        <tr>
          <td><ul id="fileList"></ul></td>
        </tr>
      </table>
      <br />
      <hr />

    </div>
    -->
    <div>
      <form action="/sendTestEmail" class="dropzone" id="my-awesome-dropzone">
          {{ csrf_field() }}
      </form>
    </div>
    <div class="alignCenter quickBooksDiv" id="quickBooksDiv">
      <input type="button" id="quickBooksButton" value="QUICKBOOKS" class=""><br>
      <input type="button" id="flushSession" value="FLUSH SESSION" class=" flushButton">
      <input type="button" id="companyInfo" value="COMPANY INFO" class=" companyButton">
      <input type="button" id="inventorySummary" value="INVENTORY" class="invetoryButton">
    </div>
  </div>
  <div class="alignCenter">
    @isset($connInfo)
    <label>State: {{$connInfo['state']}}</label><br>
    <label>Code: {{$connInfo['code']}}</label><br>
    <label>realmId: {{$connInfo['realmId']}}</label><br>
    @endisset
  </div>
  <div class="alignCenter">
    @isset($companyInfo)
    <table id="dataTable" class="dataTableStyle">
      <tr>
        <th>ID</th>
        <th>Description</th>
      </tr>
      @foreach($companyInfo as $key => $record)
      <tr>
        <td>{{$record->Id}}</td>
        <td>{{$record->Description}}</td>
      </tr>
      @endforeach
    </table>
    @endisset
    @isset($companyInfo1)
    <label>{{$companyInfo}}</label>
    @endisset
  </div>
</body>
</html>