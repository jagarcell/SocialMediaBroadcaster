<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Carriers;
use App\email;
use App\Mailinglists;
use Illuminate\Database\QueryException;

class verifiednumbers extends Model
{
    // ADDS THE PROVIDED PHONE NUMBER TO THE VERIFIED LIST
    public function addVerifiedNumber(Request $request)
    {
    	# code...
        try {
            $this->phonenumber = $request['phonenumber'];
            $this->carrier = $request['carrier'];
            $this->save();
        } catch (QueryException $e) {
            
        }
    }

    // VERIFY IF THE PHONE NUMBER IS VALID
    public function validateNumber(Request $request)
    {

/*
        $phonenumber = $request['phonenumber'];
        if($phonenumber == '12018196837'){
            return json_encode(['phonenumber' => $request['phonenumber'], 'valid' => 'valid', 'carrier' => 'carrier2']);
        }
        else{
            return json_encode(['phonenumber' => $request['phonenumber'], 'valid' => 'valid', 'carrier' => 'carrier'   ]);
        }
*/
    	# code...
		$phoneNumber = $request['phonenumber'];

		// set API Access Key
		$access_key = 'eadc16f775c37e4161342657ba126dce';

		// set phone number
		$phone_number = $request['phonenumber'];

		// Initialize CURL:
		$ch = curl_init('http://apilayer.net/api/validate?access_key='.$access_key.'&number='.$phone_number.'');  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Store the data:
		$json = curl_exec($ch);
		curl_close($ch);

		// Decode JSON response:
		$validationResult = json_decode($json, true);

		// Access and use your preferred validation result objects
		$valid = $validationResult['valid'];

//		$validationResult['country_code'];
		$carrier = $validationResult['carrier'];

        $carriers = new Carriers();

        $carrierGateway = ($carriers)->findCarrierByAlias($carrier);

        $gateway = 'generic gateway';

        if($carrierGateway === "NONE"){
            if($valid){
                $carriers->registerCarrier(['name' => 'generic', 'gateway' => $gateway, 'alias' => $carrier]);
            }
        
            return json_encode(['status' => 'OK', 'phonenumber' => $phoneNumber, 'valid' => $valid, 'carrier' => $carrier, 'gateway' => $gateway]);
        }

        $gateway = (json_decode($carrierGateway)[0])->gateway;
    	
    	return json_encode(['status' => 'OK', 'phonenumber' => $phoneNumber, 'valid' => $valid, 'carrier' => $carrier, 'gateway' => $gateway]);
    }

    public function checkIfVerified($phoneNumber)
    {
        # code...
        $verified = $this->where('phonenumber', $phoneNumber)->get();
        if(count($verified) > 0){
            return  'true';
        }
        else
        {
            return 'false';
        }
    }

    // UPDATE THE PHONE NUMBER REGISTER WITH THE NEW DATA
    public function updatePhoneRegister(Request $request)
    {
        # code...
        $id = $request['id'];
        try {
            $this->where('id', $id)->Update(
                [
                    'phonenumber' => $request['phonenumber'],
                    'carrier' => $request['carrier'],
                    'group' => $request['group'],
                    'suscriber' => $request['suscriber'],
                ]
            );
            return ['status' => 'OK', 'id' => $id];

        } catch (QueryException $e) {
            return ['status' => 'ERROR UPDATING', 'id' => $id];
        }
    }

    // RETURNS THE REQUESTED PHONE NUMBER IN EMAIL FORMAT
    public function phoneNumberEmail($phonenumber)
    {
        # code...
        $verifiedNumbers = $this->where('phonenumber', $phonenumber)->get();
        if(count($verifiedNumbers) > 0)
        {
            return ['status' => 'OK', 'carrier' => $verifiedNumbers->carrier];
        }
        else
        {
            return ['status' => 'NOT FOUND', 'carrier' => 'NONE'];
        }
    }

    public function updatePhone(Request $request)
    {
        # PARAMETERS 
        # phonenummber
        # group

        # code...
        try {
            $this->where('phonenumber', $request['phonenumber'])->update(
                ['group' => $request['group']]);
            return ['status' => 'OK', 'phonenumber' => $request['phonenumber']];
        } catch (QueryException $e) {
            return ['status' => 'ERROR UPDATING', 'phonenumber' => $phonenumber];
        }
    }

    public function updatePhoneMailList(Request $request)
    {
        # PARAMETERS 
        # phonenummber
        # maillist

        # code...
        $phonenumber = $request['phonenumber'];
        $maillist = $request['maillist'];
        try {
            $phoneregisterset = $this->where('phonenumber', $phonenumber);
            $phoneregister = ($phoneregisterset->get()[0]);

            $request['address'] = $maillist;
            $request['member'] = $phoneregister->email;

            (new email())->addRecepientsToList($request);

            $phoneregisterset->update(['emaillist' => $maillist]);
            return ['status' => 'OK', 'phonenumber' => $phonenumber];
        } catch (QueryException $e) {
            dd($phonenumber);
            return ['status' => 'ERROR UPDATING', 'phonenumber' => $phonenumber];
        }
    }

    public function addPhoneRegister(Request $request)
    {
        # PARAMETERS:
        # phonenumber
        # carrier
        # group
        # suscriber
        
        # code...

        try {
            $carrierByAlias = (new Carriers())->findCarrierByAlias($request['carrier']);
            if($carrierByAlias == 'NONE'){
                return ['status' => 'ERROR', 'message' => 'CARRIER NOT FOUND'];
            }
            $gateway = json_decode($carrierByAlias)[0]->gateway;
            
            $this->phonenumber = $request['phonenumber'];
            $this->carrier = $request['carrier'];
            $this->group = $request['group'];
            $this->suscriber = $request['suscriber'];
            $this->email = $request['phonenumber'] . '@' . $gateway;
            $this->save();
            return(['status' => 'OK']);
        } catch (QueryException $e) {
            return (['status' => 'ERROR', 'message' => $e]);
        }
    }

    public function deletePhoneRegister(Request $request)
    {
        # code...
        # PARAMETERS:
        # id
        $id = $request['id'];
        try {
            $this->where('id', $id)->delete();
        } catch (\Exception $e) {
            return (['status' => 'ERROR']);        
        }

        return ['status' => 'OK', 'id' => $id];
    }

    public function getRecepients(Request $request)
    {
        # code...
        $phoneNumber = $request['phonenumber'];

        $carrier = $request['carrier'];

        if($phoneNumber == ""){
            $recepients = $this->where('id', '>', 0);
        }
        else{
            $recepients = $this->where('phonenumber', 'LIKE', $phoneNumber . '%');
        }
        if($carrier == "All"){
            $recepients = $recepients->where('carrier', '<>', null);
        }
        else{
            $recepients = $recepients->where('carrier', '=', $carrier);
        }

        $recepients = $recepients->where('emaillist', '=', null)->get();

        return $recepients;
    }

    public function getRecepientsInList(Request $request)
    {
        # code...
        $emaillist = $request['emaillist'];

        try {
            $recepientsInList = $this->where('emaillist', '=', $emaillist);
            $status = "OK";
        } catch (QueryException $e) {
            $status = "ERROR";
        }

        return ['status' => $status, 'recepientsInList' => $recepientsInList];
    }

    public function getRecepientsByList($listAddress)
    {
        # code...
        try {
            $recepientsInList = $this->where('emaillist', '=', $listAddress)->get();
            $status = "OK";
        } catch (QueryException $e) {
            $status = "ERROR";
        }

        return ['status' => $status, 'recepientsInList' => $recepientsInList];
    }

    public function getPhoneNumber(Request $request)
    {
        # code...
        /*
        PARAMETERS:
            $phoneNumber
        */
        return $this->where('phonenumber', '=', $request['phoneNumber'])->get();
    }

    public function updateEmails()
    {
        # code...
        $phonenumbers =  $this->where('id', '>', 0)->get();

        foreach ($phonenumbers as $key => $phonenumber) {
            # code...
            try {
                $phoneregister = $this->where('phonenumber', '=', $phonenumber->phonenumber);
                $gateway = ((new Carriers())->where('alias', '=', $phonenumber->carrier)->get()[0])->gateway;
                $phoneregister->update(['email' => $phonenumber->phonenumber . "@" . $gateway,
                                        'emaillist' => null]);
            } catch (QueryException $e) {
                
            }
        }
    }

    public function updateMailLists()
    {
        # code...
        $mailLists = (new email())->getMailLists();
        foreach ($mailLists as $key => $mailList) {
            # code...
            $mailListsSet = (new Mailinglists());
            $mailListsSet->mailinglistaddress = $mailList->address;
            try {
                $mailListsSet->save();
            } catch (QueryException $e) {
                return;
            }
        }

    }
}

