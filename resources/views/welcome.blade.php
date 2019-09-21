@extends('layouts.app')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Home</title>

        @section('scripts')
        <!-- scripts -->

        <script src="//code.jquery.com/jquery-1.12.4.js"></script>
        <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript" src="/public/js/welcome.js"></script>
        <script type="text/javascript" src="/public/js/dropzone.js"></script>
        @endsection

        <!-- Fonts
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
         -->
         @section('styles')
        <!-- Styles -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="/public/css/welcome.css">
        <link rel="stylesheet" type="text/css" href="/public/css/dropzone.css">
        @endsection
    </head>
    <body>
        @section('bodymaindiv')
        {{ csrf_field() }}
        <div class="locationDiv">
            <label id="ipLocation" class="locationLabel">Your Location Is: </label>
            <img src="" id="flag" class="locationFlag">
        </div>

       <div class="messageDiv">
            <div>
              <form action="sendMailToLists" method="post" enctype="multipart/form-data" id="sendMailToLists">
                {{ csrf_field() }}
                <label>DOMAINS:</label>
                <select class="messageComponent" id="domainSelect" name="domain">
                    @foreach($domains as $key => $domain)
                    <option value="{{$domain->name}}">{{$domain->name}}</option>
                    @endforeach
                </select>
                <label>RECEPIENT LISTS</label>
                <!--
                <input type="text" name="to" id="to" class="inputTo" value="{{$to}}">
                -->
                <div class="messageComponent messageToListComponent" id="messageToListDiv">
                    <table id="mailListTable">
                        <tr>
                            <th><input type="checkbox" class="masterCheckBox"></th>
                            <th>Mailing List Name</th>
                        </tr>
                        @foreach($maillists as $key => $maillist)
                        <tr class="mailListRow">
                            <td>
                                <input type="checkbox" class="maillistCheckBox" id="{{$key}}" value="{{$maillistCheck[$key]}}">

                                <input type="hidden" name="maillistCheck[]" value="{{$maillistCheck[$key]}}" class="maillistCheck" id="sendCheck_{{$key}}">
                                <input type="hidden" name="mailistAddress[]" value="{{$maillist->address}}">
                            </td>
                            <td>{{$maillist->address}}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div class="messageComponent">
                    <label>SUBJECT:</label>
                    <textarea type="text" name="subject" id="messageSubject" class="messageComponent">{{$subject}}</textarea>
                </div>
 
                <!-- PARAMETERS TO BE USED BY JAVASCRIPT -->

                <input type="hidden" id="numberOfUploadedRows">
                <input type="hidden" id="numberOfUploadedColumns">

                <!-- SET OF INPUTS THAT HOLDS THE UPLOADED FILES. GENERATED FROM JAVASCRIPT -->
                <div hidden="" id="filesInputs">
                    <!--input type="hidden" name="images[]" value="/public/images/"-->
                </div>

                <input type="hidden" id="nextTextArea" value="0">
                <input type="hidden" id="nextImageArea" value="0">

                <!-- SET OF INPUTS THAT KEEPS TRACK OF EMAIL ELEMENTS LAYOUT. GENERATED FROM JAVASCRIPT -->
                <div hidden="" id="layoutInputs">
                    <!--input type="hidden" class="elements" name="elements[0]['type']" value="textArea">
                    <input type="hidden" class="elements" name="elements[0]['path']" value="textarea1">
                    <input type="hidden" class="elements" name="elements[1]['type']" value="imageArea">
                    <input type="hidden" class="elements" name="elements[1]['index']" value="images1" -->
                </div>
            </form>
            </div>

            <div id="layoutDiv">
           </div>

            <!-- ACTION BUTTONS TO ADD TEXT OR IMAGES ELEMENTS -->
            <div class="addTextButton">
                <input type="button" class="actionButton" value="ADD TEXT HERE" id="addTextButton">
            </div>
            <div class="addImagesButton">
                <input type="button" class="actionButton" value="ADD IMAGES HERE" id="addImagesButton">
            </div>
            <div class="messageSendButtonDiv">
                <input type="submit" value="SEND" class="messageSendButton actionButton" form="sendMailToLists">
            </div>

            <div class="imagesLayoutButtonDiv" hidden="" id="imagesLayoutButtonDiv">
                <input type="button" value="IMAGES LAYOUT" class="actionButton btn btn-default btn-sm " id="imagesLayoutButton">
            </div>
            <div id="dialog" title="Layout Config" class="leftTestDiv">
                <div>
                    Images In A Row
                </div>
                <div>
                    <input type="number" min="1" max="4" name="numberOfColumns" value="1" id="numberOfColumns">
                </div>
                <div>
                    Rows Of Images
                </div>
                <div>
                    <input type="number" min="1" max="9" name="numberOfRows" value="1" id="numberOfRows">
                </div>
            </div>
            <form action="/fileUpload" method="post" enctype="multipart/form-data" class="uploadDiv" id="imagesDropZone">
                 <div class="uploadDiv">
                    {{ csrf_field() }}
                    <!--center>
                        Select image to upload: <input type="file" name="file[]" id="fileToUpload" multiple="">
                        <input type="submit" name="Upload" value="Upload"">
                    </center-->
                    @foreach($maillists as $key => $maillist)
                        <input type="hidden" name="maillistCheck[]" class="maillistCheck" id="check_{{$key}}" value="{{$maillistCheck[$key]}}">
                        <input type="hidden" name="maillistAddress[]" value="{{$maillist->address}}">
                    @endforeach
                    <input type="hidden" name="subject" id="messageSubjectUpload" value="{{$subject}}">
                    <input type="hidden" name="message" id="messageTextUpload" value="{{$message}}">
                </div>
            </form>

                    
            <!--form action="fileUpload" 
                class="uploadDiv" method="post" enctype="multipart/form-data" 
                id="imagesDropZone">
                {{ csrf_field() }}
                <input type="file" name="file[]">
                <input type="submit" value="UPLOAD">
            </form>
            <div style="width: 100%; text-align: center;">
            <video width="120px" height="120px" controls="">
                <source src="public/images/videohtml.mp4" type="">
            </video>
            </div-->
       </div>
       @endsection
    </body>
</html>
