<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mailgun\Mailgun;
use App\test;

class testController extends Controller
{
    //
    public function __construct(){
        $this->middleware('qbconn');
    }

    public function sendTestEmail(Request $request)
    {
    	# code...
  	
		$mgClient = new Mailgun(env('MAILGUN_SECRET'));

		try {

			$mgClient->sendMessage('patmelsystems.com', array(
				'from' => 'info@patmelsystems.com',
				'to' => array('17862002117@tmomail.net', '15512147260@tmomail.net'),
				'subject' => 'TEST EMAIL',
				'text' => 'CHECK OUT THIS TEXT PLEASE'
			));
			return['status' => 'OK'];
		} catch (Exception $e) {
			return['status' => 'ERROR'];
		}
    }

    public function quickBooks(Request $request)
    {
    	# code...
    	return (new test())->quickBooks($request);
    }

    public function qbCallBack(Request $request)
    {
    	# code...
    	return (new test())->qbCallBack($request);
    }

    public function companyInfo(Request $request)
    {
        # code...
        return (new test())->companyInfo($request);
    }

    public function inventorySummary(Request $request)
    {
        # code...
        return (new test())->inventorySummary($request);
    }
}
