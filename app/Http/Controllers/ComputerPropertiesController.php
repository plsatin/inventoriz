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
        //
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

    public function showAllPropertiesOfComputerTree($id)
    {
        try {
            $computer = Computer::findOrFail($id);
            $computerClasses = WmiClass::query()->get();

            $classCount = 0;

            foreach ($computerClasses as $class)
            {
                $classPropertiesInstance = ComputerProperties::select('instance_id')->where('computer_id', $computer->id)
                    ->where('computer_properties.wmiclass_id', $class->id)->groupBy('instance_id')
                        ->get();

                $instanceId = 0;
                $classProperties = [];

                foreach ($classPropertiesInstance as $instance) {

                    $classPropertiesInstanceArray = ComputerProperties::select('computer_properties.computer_id', 'computer_properties.wmiclass_id',
                     'computer_properties.wmiproperty_id', 'computer_properties.value',
                      DB::raw("CONCAT(wmiproperties.name, ':  ', computer_properties.value) AS title"),
                       'computer_properties.instance_id', 'wmiproperties.name AS name', 'wmiproperties.description AS iconTooltip')
                        ->where('computer_id', $computer->id)
                        ->where('computer_properties.wmiclass_id', $class->id)->where('instance_id', $instance->instance_id)
                            ->join('wmiproperties', 'computer_properties.wmiproperty_id', '=', 'wmiproperties.id')
                                ->get();
                    $classPropertiesInstanceName = ComputerProperties::where('computer_id', $computer->id)
                        ->where('computer_properties.wmiclass_id', $class->id)->where('instance_id', $instance->instance_id)
                        ->where('wmiproperties.name', 'Name')
                            ->orWhere('computer_id', $computer->id)
                                ->where('computer_properties.wmiclass_id', $class->id)->where('instance_id', $instance->instance_id)
                                ->where('wmiproperties.name', 'Caption')
                                    ->join('wmiproperties', 'computer_properties.wmiproperty_id', '=', 'wmiproperties.id')
                                        ->first();


                    $classProperties[$instanceId]['id'] = $instance->instance_id;
                    $classProperties[$instanceId]['parent_id'] =  $class->id;
                    $classProperties[$instanceId]['icon'] = '/assets/img/icons/' . $computerClasses[$classCount]['icon'];
                    if($classPropertiesInstanceName) {
                        $classProperties[$instanceId]['title'] =  $classPropertiesInstanceName->value;
                    }

                    $classProperties[$instanceId]['children'] = $classPropertiesInstanceArray;

                    $instanceId ++;
                }

                $computerClasses[$classCount]->iconTooltip = $computerClasses[$classCount]['description'];
                $computerClasses[$classCount]->icon = '/assets/img/icons/' . $computerClasses[$classCount]['icon'];
                $computerClasses[$classCount]->children = $classProperties;
                $classCount ++;
            }


            $computer->children = $computerClasses;

            return response()->json($computer, 200);
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
            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Updated Successfully'));
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

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Deleted Successfully'));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }





}
