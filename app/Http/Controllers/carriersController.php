<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Carriers;

class carriersController extends Controller
{
    //
    // SHOWS THE CARRIERS VIEW 
    public function show()
    {
    	# code...
    	$carriers = (new Carriers())->where('id', '>=', 0)->get();
    	return view('carriers', ['carriers' => $carriers]);
    }

    // REMOVES A CARRIER FROM THE DATABASE
    public function removeCarrier(Request $request)
    {
    	# code...
    	// CALLS THE MODEL'S METHOD TO REMOVE A CARRIER
    	return (new Carriers())->removeCarrier($request);
    }

    // ADDS A NEW CARRIER
    public function addCarrier(Request $request)
    {
    	# code...
    	// CALLS THE MODEL'S METHOS TO ADD A NEW CARRIER
    	return (new Carriers())->addCarrier($request);
    }

    // UPDATES AN EXISTING CARRIER
    public function updateCarrier(Request $request)
    {
    	# code...
    	// CALLS THE MODEL METHOD TO UPDATE AN EXISTING CARRIER
    	return (new Carriers())->updateCarrier($request);
    }

    public function getCarriers()
    {
        # code...
        $carriers = (new Carriers())->where('id', '>=', 0)->get();
        return $carriers;
    }
}
