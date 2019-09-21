<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\email;
use Illuminate\Database\QueryException;

class Workingdomain extends Model
{
    //
    public function initialWorkingDomain()
    {
    	# code...
    	try {
    		$workingDomainSet = $this->where('id', '>', 0);
    		if(count($workingDomainSet->get()) == 0){
    			$domains = (new email())->getDomains();
    			$domainname = $domains[0]->name;
    			$this->domainname = $domainname;
    			$this->save();
    		}
    		else{
    			$domainname = ($workingDomainSet->get()[0])->domainname;
    		}
			return ['status' => 'OK', 'domainname' => $domainname];
    	} catch (QueryException $e) {
    		return ['status' => 'ERROR'];
    	}
    }

    public function setWorkingDomain(Request $request)
    {
    	# code...
    	# PARAMETERS:
    	# domainname
    	try {
	    	$workingDomainSet = $this->where('id', '>', 0);
	    	if(count($workingDomainSet->get()) == 0){
	    		$this->domainname = $request['domainname'];
    			$this->save();
	    	}
	    	else{
	    		$workingDomainSet->update(['domainname' => $request['domainname']]);
	    	}
	    	return ['status' => 'OK'];
    	} catch (QueryException $e) {
    		return ['status'=> 'ERROR'];
    	}
    }

    public function getWorkingDomain()
    {
    	# code...
    	try {
    		$workingDomainName = ($this->where('id', '>', 0)->get()[0])->domainname;
    		return ['status' => 'OK', 'domainname' => $workingDomainName];
    	} catch (QueryException $e) {
    		return ['status' => 'ERROR'];
    	}
    }
}
