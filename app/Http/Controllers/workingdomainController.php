<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Workingdomain;
class workingdomainController extends Controller
{
    //
    public function setWorkingDomain(Request $request)
    {
    	# code...
    	# PARAMETERS:
    	# domainname
    	return (new Workingdomain())->setWorkingDomain($request);
    }

    public function initialWorkingDomain()
    {
    	# code...

    	return (new Workingdomain())->initialWorkingDomain();
    }
}
