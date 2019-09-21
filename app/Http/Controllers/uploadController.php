<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\email;

use Illuminate\Support\Facades\Storage;


class uploadController extends Controller
{
    //
    public function fileUpload(Request $request)
    {
    	# code...
        $pathArray = array();
        $pathArrayChunk = array();

        $filesToUpload = $request->file('file');

        if($filesToUpload != null)
        {    
            for ($i = 0; $i < count($filesToUpload); $i++) {
                # code...
                $fileToUpload = $filesToUpload[$i];
                $path = $fileToUpload->storeAs('images', $_FILES['file']['name'][$i], 'welcome_images');
                array_push($pathArray, $path);
            }
            $pathArrayRows = count($pathArray) / 3;
            
            if(($pathArrayRows - floor($pathArrayRows)) > 0){
                $pathArrayRows = floor($pathArrayRows + 1);
            }
            $pathArrayChunk = array_chunk($pathArray, $pathArrayRows);
        }

		$email = new email();
		$domains = $email->getDomains();
		$maillists = $email->getMailLists();

        $maillistCheck = $request['maillistCheck'];

        if($request['uploadButton'] == null){
            return( ['to' => $request['to'], 'subject' => $request['subject'], 'message' => $request['message'], 'path2' => $pathArrayChunk,
            'domains' => $domains, 'maillists' => $maillists, 'maillistCheck' => $maillistCheck]);
        }
        else{
            return view('welcome', ['to' => $request['to'], 'subject' => $request['subject'], 'message' => $request['message'], 'path2' => $pathArrayChunk,
            'domains' => $domains, 'maillists' => $maillists, 'maillistCheck' => $maillistCheck]);
        }
    }
}
