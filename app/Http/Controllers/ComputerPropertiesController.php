<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;
use App\Models\Computer;
use App\Models\ComputerProperties;


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
            $computerProperties = $computer->properties()->get();

            return response()->json($classProperties, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' =>$e->getCode(),"Message"=>$e->getMessage()));
            return response()->json($computerProperties, 404);
        }
    }

    public function showOnePropertiesOfComputer($id, $class, $property)
    {
        try {

            $computer = Computer::findOrFail($id);
            $wmiclass = WmiClass::findOrFail($class);
            $wmiproperty = WmiProperty::findOrFail($property);

            $property = ComputerProperties::whereBelongsTo($computer)
            ->whereBelongsTo($wmiclass)
                ->whereBelongsTo($wmiproperty)->first();

            return response()->json($property, 201);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function create(Request $request, $id, $class, $property)
    {
        try {

            $computer = Computer::findOrFail($id);
            $wmiclass = WmiClass::findOrFail($class);
            $wmiproperty = WmiProperty::findOrFail($property);

            $property = new ComputerProperties;
            $property->computer_id = $computer->id;
            $property->wmiclass_id = $wmiclass->id;
            $property->wmiproperty_id = $wmiproperty->id;
            $property->value = $request->input('value');
            $property->instance_id = $request->input('instance_id');

            $property->save();

            return response()->json($property, 201);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function update(Request $request, $id, $class, $property)
    {
        try {
            $computer = Computer::findOrFail($id);
            $wmiclass = WmiClass::findOrFail($class);
            $wmiproperty = WmiProperty::findOrFail($property);

            $property = ComputerProperties::whereBelongsTo($computer)
                ->whereBelongsTo($wmiclass)
                    ->whereBelongsTo($wmiproperty)->update($request->all());

            return response()->json($property, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function delete($id, $class, $property)
    {
        try {

            $computer = Computer::findOrFail($id);
            $wmiclass = WmiClass::findOrFail($class);
            $wmiproperty = WmiProperty::findOrFail($property);

            $property = ComputerProperties::whereBelongsTo($computer)
                ->whereBelongsTo($wmiclass)
                    ->whereBelongsTo($wmiproperty)->delete();

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Deleted Successfully'));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }





}
