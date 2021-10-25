<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;

class ComputerPropertiesController extends Controller
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



    public function showAllPropertiesOfComputer($id)
    {
        try {

            $computer = Computer::findOrFail($id);
            // $classProperties = WmiProperty::query()->where('wmiclass_id', $wmiClass->id)->get();
            $computerProperties = $computer->properties()->get();


            return response()->json($classProperties, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' =>$e->getCode(),"Message"=>$e->getMessage()));
            return response()->json($computerProperties, 404);
        }
    }


    public function create(Request $request)
    {
        try {

            $property = ComputerProperties::create($request->all());

            return response()->json($property, 201);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $property = ComputerProperties::findOrFail($id);
            $property->update($request->all());

            return response()->json($property, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function delete($id)
    {
        try {
            ComputerProperties::findOrFail($id)->delete();

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Deleted Successfully'));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }





}
