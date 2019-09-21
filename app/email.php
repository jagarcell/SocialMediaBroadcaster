<?php
namespace App;

use vendor\guzzlehttp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\verifiednumbers;
use App\Carriers;
use App\Workingdomain;

use App\Mail\advertise;

use GuzzleHttp\Client;
use Mailgun\Mailgun;

class email extends Model
{
	public function sendEmail(Request $request)
	{
		# code...
		$to = $request['to'];
		$verifiednumber = (new verifiednumbers())->where('phonenumber', '=', $to)->get();
		if(count($verifiednumber) > 0)
		{
			$carriersgateway = (new Carriers())->where('alias', '=', $verifiednumber[0]->carrier)->get();
			if(count($carriersgateway) > 0){
				$to = $to . '@' . $carriersgateway[0]->gateway;
				Mail::to($to)->send(new advertise());
				return;
			}
			else
			{
				echo('CARRIER NOT FOUND');
				return;
			}
		}
		else
		{
			echo('PHONE NUMBER IS NOT VERIFIED');
			return;
		}
	}

	public function getMailLists()
	{
		$workingDomain = (new Workingdomain())->getWorkingDomain();
		if($workingDomain['status'] == 'ERROR'){
			return;
		}

		$mgClient = new Client(['base_uri' => 'https://api:' . env('MAILGUN_SECRET') . '@api.mailgun.net/v3/' . $workingDomain['domainname']]);

		# Issue the call to the client.
		$result = $mgClient->request("get", "lists/pages");
		$body = $result->getBody();

		$bodyJson = json_decode($body->getContents());

		return $bodyJson->items;
	}

	public function newList(Request $request)
	{
		# code...
		$mgClient = new Mailgun(env('MAILGUN_SECRET'));

		$address = $request['address'];
		$name = $request['name'];
		$description = $request['description'];

		try {
			$result = $mgClient->post("lists", array(
	    		'address'     => $address,
	    		'description' => $description,
	    		'name' => $name,
	    		'access_level' => 'members'
			));
			$resultJson = json_encode($result->http_response_body->list);
			return $resultJson;
		} catch (Mailgun\Connection\Exceptions\MissingRequiredParameters\Exception $e) {
			return "THE LIST COULDN'T BE CREATED";			
		}
	}

	public function deleteMailList(Request $request)
	{
		# code...
		$mgClient = new Mailgun(env('MAILGUN_SECRET'));

		$address = $request['address'];

		try {
			$result = $mgClient->delete('lists/' . $address);
			$resultJson = json_encode($result->http_response_body);
			return ['address' => $address];

		} catch (Mailgun\Connection\Exceptions\MissingRequiredParameters\Exception $e) {
			return "THE LIST COULDN'T BE DELETED";
		}
	}

	public function getDomains()
	{
		# code...
		$mgClient = new Mailgun(env('MAILGUN_SECRET'));
		
		# Issue the call to the client.
		$result = $mgClient->get("domains");
		return $result->http_response_body->items;
	}

	public function addMailingList(Request $request)
	{
		# code...
		// CREATES A NEW MAILING LIST AND ADDS info@domain 
		// AS A MEMBER IF SUCCESSFULLY CREATED

		$mgClient = new Mailgun(env('MAILGUN_SECRET'));

		$address = $request['address'];
		$name = $request['name'];
		$description = $request['description'];

		try {
			$result = $mgClient->post("lists", array(
	    		'address'     => $request["address"]  . "@" . $request["domain"] ,
	    		'description' => $request["description"],
	    		'name' => $request["name"],
	    		'access_level' => $request["access_level"]
			));
			$result->http_response_body->list->status = "OK";
			$resultJson = json_encode($result->http_response_body->list);

			$request['address'] = $request["address"]  . "@" . $request["domain"];
			$request['member'] = 'info@' . $request['domain'];
			
			// ADD info@domain TO THE LIST			 
			$this->addRecepientsToList($request);
			return $resultJson;
		}  catch (\Mailgun\Exception $e) {
			return json_encode(['status' => 'FAIL']);
		}
	}

	public function addRecepientsToList(Request $request)
	{
	/*
 		PARAMETERS:
	    string $address
		string $member
	*/
		# code...
		$mgClient = new Mailgun(env('MAILGUN_SECRET'));
		$address = $request['address'];
		$member = $request['member'];
//		$member = substr($member, 1);

		try {
			$mgClient->post("lists/$address/members", array(
				'address' => $member,
				'suscribed' => true
			));
			return ['status' => 'OK'];
		} catch (Exception $e) {
			return ['status' => 'ERROR'];
		}
	}

	public function recepientsMayBeAdded(Request $request)
	{
		# code...
		$verifiedNumbers = ((new verifiednumbers())->where('emaillist', '=', null)->get());
		return $verifiedNumbers;
	}

	public function members(Request $request)
	{
		# code...
		# PARAMETERS
		#################
		# name 			#
      	# address 		#
      	# description 	#
      	# access_level 	#
      	# result 		#
      	#################
		$mgClient = new Mailgun(env('MAILGUN_SECRET'));

		$address = $request['address'];
		try {
			$result = $mgClient->get("lists/$address/members/pages");
			$result->http_response_body->status = "OK";
			$resultJson = json_encode($result->http_response_body);
			return $resultJson;
		} catch (Exception $e) {
			return ["status" => "ERROR", "items" => null, "paging" => null];
		}
	}

	public function deleteMembersFromAList(Request $request)
	{
		# code...
		$mgClient = new Mailgun(env('MAILGUN_SECRET'));

		$result = array();
		$address = $request['address'];
		$members = $request['members'];

		foreach ($members as $key => $member) {
			# code...
			try {
				$mgClient->delete("lists/$address/members/$member");
				(new verifiednumbers())->where('email', '=', $member)->update(['emaillist' => null]);
				array_push($result, ['status' => 'OK', 'member' => $member]);
			} catch (Exception $e) {
				array_push($result, ['status' => 'ERROR', 'member' => $member]);
			}
		}
		return json_encode($result);
	}

	public function sendMailToLists(Request $request)
	{
		# code...
		# PARAMETERS:
		#####################################
		# domain 							#
		# maillistCheck[]					#
		# maillistAddress[]					#
		# subject 							#
		# message  		 					#
		# numberOfUploadedRows				#
		# numberOfUploadedColumns			#
		# images[]  						#
		# imagesText[] 						#
		#####################################

		$workingDomain = (new Workingdomain())->getWorkingDomain();

		$results = array('status' => 'OK');
		if($workingDomain['status'] == 'ERROR'){
			return ['status' => 'ERROR'];
		}

		$domainname = $workingDomain['domainname'];
		$from = "info@patmelsystems.com";
		$recepientsChecked = $request['maillistCheck'];
		$recepientsAddress = $request['mailistAddress'];
		$messagesubject = $request['subject'];
		$elements = $request['elements'];
		$images = $request['images'];

		$recepientLists = array();
		foreach ($recepientsChecked as $key => $checked) {
			# code...
			if($checked == "on"){
				array_push($recepientLists, $recepientsAddress[$key]);
			}
		}

		$mgClient = new Mailgun(env('MAILGUN_SECRET'));
		foreach ($recepientLists as $key => $recepientList) {
			# code...
			Mail::to($recepientList)->send(new advertise([
				'fromAddress' => $from,
				'messageSubject' => $messagesubject,
				'elements' => $elements,
				'images' => $images,
			]));
		}

		return redirect("/");
	}
}