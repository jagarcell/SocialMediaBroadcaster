<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\verifiednumbers;
use App\Carriers;

class phoneNumbersController extends Controller
{
    //
    public function show()
    {
    	# code...

    	$phoneNumbers = (new verifiednumbers())->where('id', '>', -1)->paginate(13);
    	$carriers = (new Carriers())->where('id', '>', -1)->get();

    	return view('phonenumbers', 
    		['phoneNumbers' => $phoneNumbers, 'carriers' => $carriers]);
    }

    public function updatePhoneRegister(Request $request)
    {
    	# code...
    	return (new verifiednumbers())->updatePhoneRegister($request);
    }

    public function addPhoneRegister(Request $request)
    {
        # PARAMETERS:
        # phonenumber
        # carrier
        # group
        # suscriber

        # code...
        $result = (new verifiednumbers())->addPhoneRegister($request);
        return $result;
    }

    public function deletePhoneRegister(Request $request)
    {
        # code...
        # PARAMETERS:
        # phonenumber
        $result = (new verifiednumbers())->deletePhoneRegister($request);
        return $result;
    }
}
