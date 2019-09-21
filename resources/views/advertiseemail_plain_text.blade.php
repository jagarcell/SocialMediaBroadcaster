{{$messageSubject}}

@foreach($elements as $key => $element)
	@if($element['type'] == "textArea")
		{{$element['text']}}
	@else
        @foreach($images[$element['index']] as $key => $imageRow)
            @foreach($imageRow as $key1 => $imageRowCol)
                @foreach($imageRowCol as $key2 => $image)
                    http://ba3a58ee.ngrok.io/{{$image['path']}}
                @endforeach
 					{{$imageRowCol[0]['tag']}}
            @endforeach                            
        @endforeach
	@endif
@endforeach
