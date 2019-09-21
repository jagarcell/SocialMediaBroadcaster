<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\verifiednumbers;
use App\Carriers;
use App\Mail\advertise;
use App\Http\Controllers\Controller;
use App\email;

use GuzzleHttp\Client;
use Mailgun\Mailgun;

class emailController extends Controller
{
	public function show()
	{
		# code...
		return view('email');
	}
	public function sendEmail(Request $request)
	{
		# code...
		(new email())->sendEmail($request);
		return;
	}

	public function getMailLists()
	{
		# code...
		# Instantiate the client.
		$items = (new email())->getMailLists();
		return view('mailinglists', ['items' => $items]);
	}

	public function mailLists()
	{
		# code...
		return (new email())->getMailLists();

	}

	public function deleteMailList(Request $request)
	{
		# code...
		$result = (new email())->deleteMailList($request);
		return $result;
	}

	public function createMailList()
	{
		# code...
		$domains = (new email())->getDomains();
		return view('createmaillist', ['domains' => $domains]);
	}

	public function addMailingList(Request $request)
	{
		# code...
		$result = (new email())->addMailingList($request);
		return $result;
	}

	public function addMemberToAList(Request $request)
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


		$members = json_decode((new email())->members($request));
		return view('addmembertoalist',
		 ['address' => $request['address'],
		 'name' => $request['name'],
		'description' => $request['description'],
		'access_level' => $request['access_level'],
		'members' => $members]);
	}

	public function addRecepients(Request $request)
	{
		# code...
		$verifiedNumbers = (new email())->recepientsMayBeAdded($request);

		$carriers = (new Carriers())->getCarriers();

		return view('addrecepients', 
			[
				'address' => $request['address'],
				'verifiednumbers' => $verifiedNumbers,
				'carriers' => $carriers
			]);
	}

	public function deleteMembersFromAList(Request $request)
	{
		# code...
		$result = (new email())->deleteMembersFromAList($request);
		return $result;
	}

	public function sendMailToLists(Request $request)
	{
		# code...
		# PARAMETERS:

		# from
		# to (array())
		# messagetext
		# messagehtml
		# messageattachments (array())
		return (new email())->sendMailToLists($request);
	}

	public function sendMailToLists1(Request $request)
	{
		# code...
		# PARAMETERS:

		# from
		# to (array())
		# messagetext
		# messagehtml
		# messageattachments (array())
		
		return $request['path2'];
		return (new email())->sendMailToLists1($request);
	}
}