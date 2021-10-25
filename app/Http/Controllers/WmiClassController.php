<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;

class WmiClasss extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }



    public function showAllWmiClasses(Request $request) 
    {
        try {
            $wmiClasses = WmiClass::all();

            return response()->json($wmiClasses);

        } catch (\Exception $e) {
            $responseObject = array("Response"=>"Error","data"=>array("Code"=>$e->getCode(),"Message"=>$e->getMessage()));
            return response()->json($responseObject, 404);
        }

    }





    public function showOneClass($id)
    {
        try {
            return response()->json(WmiClass::findOrFail($id));
        } catch (\Exception $e) {
            $responseObject = array("Response"=>"Error","data"=>array("Code"=>$e->getCode(),"Message"=>$e->getMessage()));
            return response()->json($responseObject, 404);
        }

    }




}
