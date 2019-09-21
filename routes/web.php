<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create somethit!
|
*/
use App\Http\Controllers\emailController;
use App\Http\Controllers\carriersController;
use App\Http\Controllers\uploadController;

use Illuminate\Http\Request;

use App\email;
use App\Workingdomain;
Route::get('/', function (){
	// THIS IS THE HOME PAGE ROUTE

	// LET'S GET A DOMAIN TO START TO WORK WITH
	$result = (new Workingdomain())->initialWorkingDomain();
	// IF WE GOT A DOMAIN TO START TO WORK WITH ...
	if($result['status'] == 'OK'){
		// ... THEN LET'S GET THE INITIAL PARAMETERS FOR THE HOME PAGE
		$email = new email();
		// GET THE LIST OF DOMAINS
		$domains = $email->getDomains();
		// GET THE MAIL LISTS
		$maillists = $email->getMailLists();
		// EMPTY ARRAY FOR INITIAL UPLOADED IMAGES PARAMETER
		$path2 = array();
		// EMPTY ARRAY FOR INITIAL CHECKED MAILLIST TO SEND MAIL TO
		$maillistCheck = array();

		// LET'S SET ALL THE MAILLISTS AS UNCHECKED
		for ($i=0; $i < count($maillists); $i++) { 
			# code...
			// UNCKECK EACH MAIL LIST FOR THE HOME PAGE
			array_push($maillistCheck, "off");
		}

	    return view('welcome', ['to' => '', 'subject' => '', 'message' => '', 'path2' => $path2, 'maillists' => $maillists, 'maillistCheck' => $maillistCheck, 'domains' => $domains]);
	}
	else{
		// WE COULDN'T GET AN INITIAL WORKING DOMAIN
		echo( "ERROR INITIALIZING ");
	}
});

Route::get('/welcome', function(Request $request){

    return view('welcome', ['to' => $request['to'], 'subject' => $request['subject'], 'message' => $request['message'], 'path2' => $request['path2'], 'maillists' => $request['maillists'], 'maillistCheck' => $request['maillistCheck'], 'domains' => $request['domains']]);
});

/*
Route::post('/{to}/{message}', function ($to, $message){
	$email = new email();
	$domains = $email->getDomains();
	$maillists = $email->getMailLists();
    return view('welcome', ['to' => $to, 'message' => $message, 'path' => $path,  'maillists' => $maillists, 'domains' => $domains]);
});
*/

Route::get('/sendText', 'textMsgController@send');

Route::get('/admin', 'adminController@show');

Route::get('/addCarrier', 'adminController@addCarrier');

Route::get('/updateCarrier', 'adminController@updateCarrier');

Route::get('/verification', 'verificationController@show');

Route::get('/addNumberToVerify', 'verificationController@addNumberToVerify');

Route::get('/getPendingToVerify', 'verificationController@getPendingToVerify');

Route::get('/getPhoneNumber', 'verificationController@getPhoneNumber');

Route::get('/removePhoneNumber', 'verificationController@removePhoneNumber');

Route::get('/updatePhoneNumber', 'verificationController@updatePhoneNumber');

Route::get('/updatePhone', 'verificationController@updatePhone');

Route::get('/validateNumber', 'verificationController@validateNumber');

Route::get('/verifyPhoneNumbers', 'verificationController@verifyPhoneNumbers');

Route::get('/updateCarrierGateway', 'verificationController@updateCarrierGateway');

Route::get('/updatePhoneMailList', 'verificationController@updatePhoneMailList');

Route::get('/updateEmails', 'verificationController@updateEmails');

Route::get('/updateMailLists', 'verificationController@updateMailLists');

Route::get('/carriers', 'carriersController@show');

Route::get('/removeCarrier', 'carriersController@removeCarrier');

Route::get('/addCarrier', 'carriersController@addCarrier');

Route::get('/updateCarrier', 'carriersController@updateCarrier');

Route::get('/getCarriers', 'carriersController@getCarriers');

Route::get('/phonenumbers', 'phoneNumbersController@show');

Route::get('/updatePhoneRegister', 'phoneNumbersController@updatePhoneRegister');

Route::get('/addPhoneRegister', 'phoneNumbersController@addPhoneRegister');

Route::get('/email', 'emailController@show');

Route::get('/sendEmail', 'emailController@sendEmail');

Route::get('/getMailLists', 'emailController@getMailLists');

Route::get('/createMailList', 'emailController@createMailList');

Route::get('/deleteMailList',  'emailController@deleteMailList');

Route::get('/addMailingList', 'emailController@addMailingList');

Route::get('/addMemberToAList', 'emailController@addMemberToAList');

Route::get('/deleteMembersFromAList', 'emailController@deleteMembersFromAList');

Route::get('/addRecepients', 'emailController@addRecepients');

Route::post('/sendMailToLists', 'emailController@sendMailToLists');

Route::post('/fileUpload', 'uploadController@fileUpload');

Route::get('/getRecepients', 'addRecepientController@getRecepients');

Route::get('/getRecepientsInList', 'addRecepientController@getRecepientsInList');

Route::get('/addRecepientsToList', 'addRecepientController@addRecepientsToList');

Route::get('/setWorkingDomain', 'workingdomainController@setWorkingDomain');

Route::get('/initialWorkingDomain', 'workingdomainController@initialWorkingDomain');

Route::post('/sendTestEmail', 'testController@sendTestEmail');

Route::get('/test', function(Request $request){
	return view('test');
});

Route::get('/textPreview', function(){
	return view('advertiseemail');
});

Route::get('/ipLocationKey', function(Request $request){
	# code...
	// set IP address and API access key 
	$access_key = env('IPSTACK_KEY');
	return $access_key;

	// Initialize CURL:
	$ch = curl_init('http://api.ipstack.com/check?access_key='.$access_key.'');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Store the data:
	$json = curl_exec($ch);
	curl_close($ch);
	// Decode JSON response:
	$api_result = json_decode($json, true);
	// Output the "capital" object inside "location"
	return($api_result);
});

Route::get('/zonesCheck', function(){
	// Initialize CURL:
	$access_key = env('AMDOREN_KEY');
	$ch = curl_init('https://www.amdoren.com/api/locations.php?api_key='.$access_key.'');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Store the data:
	$json = curl_exec($ch);
	curl_close($ch);
	// Decode JSON response:
	$api_result = json_decode($json, true);
	// Output the "capital" object inside "location"
	
	return($api_result);
});

Route::get('/timeZone', function(){
	// Initialize CURL:
	$access_key = env('AMDOREN_KEY');
	$ch = curl_init('https://www.amdoren.com/api/timezone.php?api_key='.$access_key.'&loc=New+York');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Store the data:
	$json = curl_exec($ch);
	curl_close($ch);
	// Decode JSON response:
	$api_result = json_decode($json, true);
	// Output the "capital" object inside "location"
	
	return($api_result);
});

Route::get('/clock', function(){
	return view('clock');
});

Route::get('/quickBooks', 'testController@quickBooks');

Route::get('qbCallBack', 'testController@qbCallBack');

Route::get('/flushSession', function()
{
	# code...
	session()->flush();
});

Route::get('/companyInfo', 'testController@companyInfo');

Route::get('/inventorySummary', 'testController@inventorySummary');
