<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\verifiednumbers;

class addRecepientController extends Controller
{
    //
    public function getRecepients(Request $request)
    {
    	# code...
    	return (new verifiednumbers())->getRecepients($request);
    }

    public function getRecepientsInList(Request $request)
    {
    	# code...
    	return (new verifiednumbers())->getRecepientsInList($request);
    }

    public function addRecepientsToList(Request $request)
    {

    /* PARAMETERS:
	    string $address
		string $member
	*/
    	# code...
    	return (new email())->addRecepientsToList($request);
    }
}
