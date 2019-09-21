<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Carriers extends Model
{
    // REMOVES A CARRIER FORM THE DATABASE
    public function removeCarrier(Request $request)
    {
    	# code...
    	$id = $request['id'];
    	$recordToRemove = $this->where('id', $id);
    	$status = 'NONE';

    	try {
    		$recordToRemove->delete();
    		$status = "OK";	
    	} catch (Exception $e) {
    		$status = 'ERROR';
    	}

    	return ['status' => $status, 'id' => $id];
    }

    // ADDS A NEW CARRIER TO THE DATABASE
    public function addCarrier(Request $request)
    {
    	# code...
    	$this->name = $request['name'];
    	$this->gateway = $request['gateway'];
    	$this->alias = $request['alias'];

    	$status = 'NONE';

    	$carrier = $this->where('name', $this->name)->get();

    	try {
    		if(count($carrier) == 0)
			{
    	    		$this->save();
    	    		// IF NO EXCEPTION FOR SAVE STATUS IS OK
    	    		$status = 'OK';
        	}
        	else
        	{
        		$status = "CARRIER ALREADY REGISTERED";
        		return['status' => $status];
        	}
    	} catch (Exception $e) {
    		// IF WE GET AN EXCEPTION FOR SAVE THE STATUS IS ERROR
    		$status = 'ERROR';
    		return['status' => $status];
    	}

    	return [
    		'status' => $status, 
    		'id' => $this->id, 
    		'name' => $this->name,
    		'gateway' => $this->gateway,
    		'alias' => $this->alias
    	];
    }

    // UPDATE AN EXISTING CARRIER
    public function updateCarrier(Request $request)
    {
    	# code...
    	$id = $request['id'];
    	$status = 'NONE';

    	try {
    		// LET'S TRY TO FIND THE CARRIER IN THE DATABASE  
	    	$recordToUpdate = $this->where('id', $id);

	    	if(count($recordToUpdate->get()) > 0){
	    		// AND IF IT IS FOUND LET'S UPDATE IT TO THE NEW VALUES
		    	$name = $request['name'];
		    	$gateway = $request['gateway'];
		    	$alias = $request['alias'];

	    		$recordToUpdate->update(['name' => $name, 'gateway' => $gateway, 'alias' => $alias]);
	    		// UPDATE SUCCESS
	    		$status = 'OK';
    		}
    	} catch (Exception $e) {
    		// ERROR UPDATING
    		$status = 'ERROR';
    	}

    	// RETURN THE STATUS ALONG WITH THE CARRIER RECORD ID
    	return ['status' => $status, 'id' => $id];
    }

    //FINDS A CARRIER BY IT'S ID
    public function findCarrierById(Request $request)
    {
    	# code...
    	$id = $request['id'];
    	// SEARCH FOR A RECORD WITH THIS ID
    	$carrier = $this->where('id', $id)->get();
    	if(count($carrier) > 0){
    		// IF A RECORD IS FOUND RETURN IT'S PROPERTIES
    		return ['status' => 'OK', 'name' => $carrier->name, 'gateway' => $carrier->gateway, 'alias' => $carrier->alias];
    	}
    	else{
    		// IF NO RECORD WAS FOUND RETURN AN ERROR STATUS
    		return ['status' => 'ERROR'];
    	}
    }

    // FINDS A CARRIER GIVEN ITS NAME
    public function findCarrierByName($name)
    {
    	# code...
    	$carrierByName = $this->where('name', $name)->get();
    	if(count($carrierByName) > 0){
	    	return ['carrierByName' => $carrierByName];
    	}
    	else{
    		return ['carrierByName' => 'NONE'];
    	}
    }

    // FINDS A CARRIER GIVEN IT'S ALIAS
    public function findCarrierByAlias($alias)
    {
    	# code...
    	$carrierByAlias = $this->where('alias', $alias)->get();
    	if(count($carrierByAlias) > 0){
    		return json_encode($carrierByAlias);
    	}
    	else{
    		return ('NONE');
    	}
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
    	$this->name = $newCarrier['name'];
    	$this->gateway = $newCarrier['gateway'];
    	$this->alias = $newCarrier['alias'];
    	try {
    		$this->save();
    		return $this->id;
    	} catch (Exception $e) {
    		return -1;
    	}
    }

    public function getCarriers()
    {
        # code...

        return $this->where('id', '>=', 0)->get();
    }
}
