<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
USE App\numberstoverify;
use App\verifiednumbers;
use App\Carriers;

class verificationController extends Controller
{
    // SHOWS THE PHONE VERIFICATION FORM
	public function show()
	{
		# GET THE EXISTING NUMBERS PENDING TO BE VERIFIED
		$numbersToVerify = (new numberstoverify())->where('id', '>=', 0)->orderBy('phonenumber')->get();

		# OPEN THE VERIFICATION VIEW WITH THE EXISTING NUMBERS TO VERIFY
		return view('verification',
			['numberstoverify' => $numbersToVerify]
		);
	}

	// ADD $phoneNumber TO BE VERIFIED TO THE DATABASE
	public function addNumberToVerify(Request $request)
	{
		# CREATE AN INSTANCE OF THE MODEL numberstoverify.
		$numberstoverify = new numberstoverify();
		# CALL THE METHOD TO ADD THE NEW PHONE NUMBER
		return $numberstoverify->addNumberToVerify($request['phonenumber']);
	}

	// ADD THE PHONENUMBER TO THE VERIFIED NUMBERS TABLE
    public function addAsVerified($phoneNumber)
    {


    }

	// RETURN THE PHONENUMBERS THA HAVE THE PENDING STATUS
    public function getPendingToVerify()
    {
		# CREATE AN INSTANCE OF THE MODEL numberstoverify.
    	$numbersToVerify = (new numberstoverify())->where('status', 'PENDING')->get();
    	# RETURN THE EXISTING PENDING TO VERIFY PHONE NUMBERS LIST IN JSON FORMAT
        return ($numbersToVerify);
    }

	// REMOVES THE SUCCESFULLY VERIFIED NUMBERS FROM THE PENDING LIST
    public function removePhoneNumber(Request $request){

    	return (new numberstoverify())->removePhoneNumber($request);
    }

	// REMOVES A PHONE NUMBER FROM THE DATABASE
    public function updatePhoneNumber(Request $request){

    	return (new numberstoverify())->updatePhoneNumber($request);
    }

    // VALIDATES THE PHONE NUMBER THROUGH THE VALIDATION SERVICE API
    public function validateNumber(Request $request)
    {
    	# code...
    	return (new verifiednumbers())->validateNumber($request);
    }

    // ADDS THE PHONE NUMBER TO THE VERIFIED NUMBERS LIST
    public function addVerifiedNumber(Request $request)
    {
    	# code...
    	return (new verifiednumbers())->addVerifiedNumber($request);
    }

    // VALIDATE A LIST OF PHONE NUMBERS AND UPDATE THE VALIDATED PHONE NUMBERS TABLE
    public function verifyPhoneNumbers()
    {
        # code...
        // GET THE PHONE NUMBERS THAT ARE PENDING FOR VERIFICATION
        $pendingToVerify = $this->getPendingToVerify();
        // WALK THE THE LIST OF PENDING TO VALIDATE EACH ONE
        foreach ($pendingToVerify as $key => $pending) {
            # code...
            // CHECK IF THE PHONE NUMBER WAS PREVIOUSLY VERIFIED
            $verifiednumbers = new verifiednumbers();
            $verified = $verifiednumbers->checkIfVerified($pending->phonenumber);

            if($verified == 'false'){
                // IF THE NUMBER IS NOT YET VERIFIED 
                // VALIDATE PHONE NUBER USING THE API
                $request = new Request();
                $request['phonenumber'] = $pending->phonenumber;
                $verifyResult = json_decode($this->validateNumber($request));

                if($verifyResult->valid == 'valid' && strlen($verifyResult->carrier) > 0){
                    try {
                        // IF THE PHONE NUMBER IS VALID LET'S REGISTER
                        // IT IN THE VERIFIED NUMBERS TABLE AND ... 
                        $verifiednumbers->phonenumber = $verifyResult->phonenumber;
                        $verifiednumbers->carrier = $verifyResult->carrier;
                        $verifiednumbers->group = 'DEFAULT';
                        $verifiednumbers->email = $verifyResult->phonenumber . "@" . $verifyResult->gateway;
                        $verifiednumbers->save();

                        // ... REMOVE IT FROM THE
                        // PENDING TO VERIFY TABLE
                        (new numberstoverify())->where('id', $pending->id)->delete();

                        // WE CHANGE THE STATUS TO SHOW FROM PENDING TO VERIFIED 
                        $pendingToVerify[$key]['status'] = 'VERIFIED';

                        // CHECK IF THIS CARRIER IS ALREADY REGISTERED
                        $carrierRegistered = json_decode($this->isCarrierRegistered($verifyResult->carrier));


                        if($carrierRegistered == 'NONE'){
                            // IF IT IS NOT REGISTERED THEN LET'S REGISTER IT WITH A
                            // 'generic name' and a 'generic gateway' and the 'alias'
                            // THAT WE GOT FROM THE VERIFICATION API
                            $this->registerCarrier(['name' => 'genericName', 'gateway' => 'genericGateway', 'alias' => $verifyResult->carrier]);
                            // LET'S SET THE alias FROM THE VERIFICATION RESULT
                            // TO THE CARRIER TO BE SHOWN IN THE RESULTS TABLE
                            $pendingToVerify[$key]['carrier'] = $verifyResult->carrier;
                            $pendingToVerify[$key]['gateway'] = 'genericGateway';
                        }
                        else{
                            // LET'S SET THE alias ALREADY REGISTERED IN THE DB
                            // TO THE CARRIER TO BE SHOWN IN THE RESULTS TABLE
                            $pendingToVerify[$key]['carrier'] = $carrierRegistered[0]->alias;
                            $pendingToVerify[$key]['gateway'] = $carrierRegistered[0]->gateway;
                        }
                    } catch (Exception $e) {
                        // NOTHING TO DO HERE ON AN ERROR
                    }
                }
                else{
                    // IF WE GET A RESULT DIFERRENT TO VALID FROM THE NUMBER VERIFICATION
                    // API THEN WE SET THIS RESULT TO BE SHOWN IN THE STATUS
                    if(strlen($verifyResult->carrier) == 0){
                        $pendingToVerify->status = 'NO CARRIER';
                    }
                    else{
                        $pendingToVerify[$key]['status'] = $verifyResult->valid;
                    }
                    $pendingToVerify[$key]['carrier'] = $verifyResult->carrier;
                }
            }
            else{
                // AS THE NUMBER WAS ALREADY VERIFIED WE CHANGE 
                // THE STATUS TO SHOW TO 'ALREADY VERIFIED'
                $pendingToVerify[$key]['status'] = 'ALREADY VERIFIED';
                $pendingToVerify[$key]['carrier'] = 'ALREADY VERIFIED';
            }
        }

        // RETURN THE VALUES TO BE SHOWN IN NUMBERS 
        return $pendingToVerify;
    }

    // CHECK IF THIS CARRIER NAME IS ALREADY REGISTERED
    public function isCarrierRegistered($carrierName)
    {
        # code...
        $carriers = new Carriers();
        $carrierByAlias = $carriers->findCarrierByAlias($carrierName);
        return $carrierByAlias;
    }

    // REGISTER A NEW CARRIER
    public function registerCarrier($newCarrier)
    {
        /*
            name
            gateway
            carrier
        */
        # code...
        $carriers = new Carriers();
        return $carriers->registerCarrier($newCarrier);
    }

    // UPDATES CARRIER GATEWAY
    public function updateCarrierGateway(Request $carrierData)
    {
        # code...
        $rowId = $carrierData['rowId'];
        // CHECK IF THIS CARRIER IS ALREADY REGISTERED
        $carrierRegistered = json_decode($this->isCarrierRegistered($carrierData['alias']));

        if($carrierRegistered != 'NONE'){
            try {
                // IF THE CARRIER IS REGISTERED AND THE NEW GATEWAY VALUE DIFFERS
                // TO THE ONE REGISTERED THEN LET'S UPDATE THE GATEWAY VALUE
                if($carrierRegistered[0]->gateway != $carrierData['gateway']){
                    $carrier = new Carriers();
                    $request = new Request();
                    // WE KEEP ALL OTHER VALUES THAN GATEWAY THE SAME VALUE
                    $request['id'] = $carrierRegistered[0]->id;
                    $request['name'] = $carrierRegistered[0]->name;
                    $request['alias'] = $carrierRegistered[0]->alias;

                    // THIS IS THE NEW GATEWAY ENTERED BY THE USER
                    $request['gateway'] = $carrierData['gateway'];

                    // PROCEED TO UPDATE
                    $carrier->updateCarrier($request);
                }
            } catch (Exception $e) {
                
            }
        }
        // RETURN THE ROW ID FOR HTML DELETEION PURPOSE
        return ['rowId' => $rowId];
    }

    public function updatePhone(Request $request)
    {
        # code...
        $result = (new verifiednumbers())->updatePhone($request);
        return $result;
    }

    public function getPhoneNumber(Request $request)
    {
        # PARAMETERS:
        # $phoneNumber
        
        # code...
        return (new verifiednumbers())->getPhoneNumber($request);
    }

    public function updatePhoneMailList(Request $request)
    {
        # PARAMETERS 
        # phonenummber
        # maillist

        # code...
        return (new verifiednumbers())->updatePhoneMailList($request);
    }

    public function updateEmails()
    {
        # code...
        return (new verifiednumbers())->updateEmails();
    }

    public function updateMailLists()
    {
        # code...
        return (new verifiednumbers())->updateMailLists();
    }
}
