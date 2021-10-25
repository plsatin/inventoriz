<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;

class WmiPropertyController extends Controller
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



    public function showAllPropertiesOfClass($id)
    {
        try {

            $wmiClass = WmiClass::findOrFail($id);
            $classProperties = WmiProperty::query()->::where('wmiclass_id', $wmiClass->id)->get();


            return response()->json($classProperties, 200);
        } catch (\Exception $e) {
            $responseObject = array("Response"=>"Error","data"=>array("Code"=>$e->getCode(),"Message"=>$e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }



}
