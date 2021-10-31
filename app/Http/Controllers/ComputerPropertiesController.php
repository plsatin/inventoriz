<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;
use App\Models\Computer;
use App\Models\ComputerProperties;

use DB;

class ComputerPropertiesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles');
    }



    public function showAllPropertiesOfComputer($id)
    {
        try {
            $computer = Computer::findOrFail($id);
            $computerProperties = $computer->properties()->get();

            return response()->json($computerProperties, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' =>$e->getCode(),"Message"=>$e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }



    public function showOnePropertyOfComputer($id, $class, $property)
    {
        try {

            $computer = Computer::findOrFail($id);
            $wmiclass = WmiClass::findOrFail($class);
            $wmiproperty = WmiProperty::where('wmiclass_id', $wmiclass->id)->findOrFail($property);

            $property = ComputerProperties::query()->whereBelongsTo($computer)
                ->whereBelongsTo($wmiclass)
                    ->whereBelongsTo($wmiproperty)->firstOrFail();

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
            $wmiproperty = WmiProperty::where('wmiclass_id', $wmiclass->id)->findOrFail($property);

            // $property = new ComputerProperties;
            // $property->computer_id = $computer->id;
            // $property->wmiclass_id = $wmiclass->id;
            // $property->wmiproperty_id = $wmiproperty->id;
            // $property->value = $request->input('value');
            // $property->instance_id = $request->input('instance_id');

            // $property->save();

            $property = ComputerProperties::updateOrCreate(
                ['computer_id' => $computer->id, 'wmiclass_id' => $wmiclass->id, 'wmiproperty_id' => $wmiproperty->id, 'instance_id' => $request->input('instance_id')],
                ['value' => $request->input('value')]
            );


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
            $wmiproperty =WmiProperty::where('wmiclass_id', $wmiclass->id)->findOrFail($property);

            $property = ComputerProperties::whereBelongsTo($computer)
                ->whereBelongsTo($wmiclass)
                    ->whereBelongsTo($wmiproperty)->update($request->all());

            // dd($property);
            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Computer property ' . $wmiproperty->name . ' of WMI Class ' . $wmiclass->name . ' updated successfully'));
            return response()->json($responseObject, 200);
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
            $wmiproperty = WmiProperty::where('wmiclass_id', $wmiclass->id)->findOrFail($property);

            $property = ComputerProperties::whereBelongsTo($computer)
                ->whereBelongsTo($wmiclass)
                    ->whereBelongsTo($wmiproperty)->delete();

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Computer property ' . $wmiproperty->name . ' of WMI Class ' . $wmiclass->name . ' deleted Successfully'));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function deleteWmiClass($id, $class)
    {
        try {

            $computer = Computer::findOrFail($id);
            $wmiclass = WmiClass::findOrFail($class);

            $property = ComputerProperties::whereBelongsTo($computer)
                ->whereBelongsTo($wmiclass)->delete();

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Computer properties of WMI Class ' . $wmiclass->name . ' deleted successfully'));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }




}
