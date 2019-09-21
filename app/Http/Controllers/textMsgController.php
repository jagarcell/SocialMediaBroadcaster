<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client; 

class textMsgController extends Controller
{
    //
    //
	public function send(Request $request)
	{
		$to = $request['to'];
		$message = $request['message'];

		$account_sid = 'AC66f4c9bdf4d0b4c97a11d5ad3cb09c08'; 
		$auth_token = '70925852b06a4a49c55473da5d9b23a9'; 
		$client = new Client($account_sid, $auth_token); 
		 
		$messages = $client->messages->create($to, array( 
		        'From' => "+12019921479",  
		        'Body' => $message,      
	  	));
	}
}
