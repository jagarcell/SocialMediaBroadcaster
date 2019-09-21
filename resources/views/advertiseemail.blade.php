<!Doctype Html>
<Html>
	<head>
        <link rel="stylesheet" type="text/css" href="/public/css/advertiseemail.css">
        <link rel="stylesheet" type="text/css" href="/public/css/welcome.css">
        <link rel="stylesheet" type="text/css" href="/public/css/dropzone.css">
	</head>

	<body>
        <!-- PARAMETERS:
            $fromAddress
            $messageSubject
            $elements[n][type/text/index]
            $images[n][row][col][imageIndex]
        -->

        <div style="font-size: 24px" class="uploadDiv">
            @foreach($elements as $key => $element)
                @if($element['type'] == "textArea")
                <div style="text-align: center;">
                    <label>{{$element['text']}}</label>
                </div>
                @else
                <div>
                    <table style="width: 100%;">
                        @foreach($images[$element['index']] as $key => $imageRow)
                        <tr>
                            @foreach($imageRow as $key1 => $imageRowCol)
                            <td>
                                @foreach($imageRowCol as $key2 => $image)
                                <div>
                                    <img style="width: 120px; height: 120px;" src="{{ $message->embed($image['path']) }}">
                                </div>
                                @endforeach
                                <div>
                                    <label style="width: 98%; text-align: center;">{{$imageRowCol[0]['tag']}}</label>
                                </div>
                            </td>
                            @endforeach                            
                        </tr>
                        @endforeach
                    </table>
                </div>
                @endif
            @endforeach
        </div>
        <!--div style="width: 100%; text-align: center;">
            <video width="240px" height="240px" controls="">
                <source src="http://ba3a58ee.ngrok.io/public/images/videohtml.mp4" type="video/mp4">
                <a href="http://ba3a58ee.ngrok.io/public/images/videohtml.mp4">CLICK HERE TO WATCH THE VIDEO</a>
            </video>
        </div-->
	</body>
</Html>