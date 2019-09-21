<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class numberstoverify extends Model
{
    // ADD A PHONE NUMBER TO THE PENDING TO VERIFY TABLE
    public function addNumberToVerify($phoneNumber)
    {
    	// ASSIGN BOTH phonenumber AND status PROPERTIES
		$this->phonenumber = $phoneNumber;
		$this->status = 'PENDING';
		try {
			// TRY TO ADD THE NEW PHONE NUMBER
			$this->save();
			// RETURN THE MODEL IN JSON FORMAT
			return json_encode($this);
		} catch (Exception $e) {
			// RETURN ERROR STRING
			return 'ERROR';
		}
    }

	// REMOVE A GIVEN PHONE NUMBER RECORD BY IT'S ID
    public function removePhoneNumber(Request  $request)
    {
    	$id = $request['id'];
    	$status = "NONE";
    	$recordToRemove = $this->where('id', $id);
    	try {
    		$recordToRemove->delete();
    		$status = "OK";
    	} catch (Exception $e) {
    		$status = "ERROR";
    	}
    	return ['status' => $status, 'id' => $id];
    }

	// UPDATE A GIVEN PHONE NUMBER 
    public function updatePhoneNumber(Request $request)
    {
    	$phonenumber = $request['phonenumber'];
    	$id = $request['id'];
    	$status = "NONE";

    	try {
    		// TRY TO UPDATE THE PHONE NUMBER
	    	$phoneRecord = $this->where('id', $id)->update(['phonenumber' => $phonenumber]);
	    	// RETURN STATUS OK ON SUCCESS
	    	$status = "OK";
    	} catch (Exception $e) {
    		// RETURN STATUS ERROR IN CASE OF FAILURE
    		$status = "ERROR";
    	}

    	// BACK WITH THE UPDATE STATUS AND RECORD ID
    	return ['status' => $status, 'id' => $id];
    }
}

