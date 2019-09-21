<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Carriers;

class adminController extends Controller
{
    //
	// Shows the Administration Panel
    public function show()
    {
    	$carriers = (new Carriers())->where('id', '>=' , 0)->get();
    	return view('admin',
    		['carriers' => $carriers]
    	);
    }

    public function addCarrier(Request $request)
    {
    	$name = $request->name;
    	$gateway = $request->gateway;
    	$alias = $request->alias;
        try {
            $carriers = new Carriers();
            $carriers->name = $name;
            $carriers->gateway = $gateway;
            $carriers->alias = $alias;
            $carriers->save();
            return("OK");
        } catch (Exception $e) {
            return("ERROR");
        }
    }

    public function updateCarrier(Request $request)
    {
        # code...
        $name = $request->name;
        try {
            $carriers = (new Carriers())->where('name', $name)->get();
            $carrier = $carriers[0];
            $carrier->name = $name;
            $carrier->gateway = $request->gateway;
            $carrier->alias = $request->alias;
            $carrier->update();

            return("OK");
        } catch (Exception $e) {
            return("ERROR");
        }
    }
}
