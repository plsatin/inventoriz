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
            // $classProperties = WmiProperty::query()->where('wmiclass_id', $wmiClass->id)->get();
            $classProperties = $wmiClass->properties()->get();


            return response()->json($classProperties, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' =>$e->getCode(),"Message"=>$e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }


    public function showOnePropertiesOfClass($id, $property)
    {
        try {
            $wmiClass = WmiClass::findOrFail($id);
            $classProperty = WmiProperty::where('wmiclass_id', $wmiClass->id)->findOrFail($property);

            return response()->json($classProperty, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' =>$e->getCode(),"Message"=>$e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }



/* Для этих функций маршруты отключены */

    public function create(Request $request, $id)
    {
        try {
            $wmiClass = WmiClass::findOrFail($id);
            $wmiProperty = $wmiClass->properties()->create($request->all());

            return response()->json($wmiProperty, 201);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function update(Request $request, $id, $property)
    {
        try {
            $wmiClass = WmiClass::findOrFail($id);
            $wmiProperty = $wmiClass->properties()->findOrFail($property);
            $wmiProperty->update($request->all());

            return response()->json($wmiProperty, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function delete($id, $property)
    {
        try {
            $wmiClass = WmiClass::findOrFail($id);
            $wmiProperty = $wmiClass->properties()->findOrFail($property);
            $wmiProperty->delete();

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Deleted Successfully'));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }



}
