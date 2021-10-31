<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;
use App\Models\Computer;
use App\Models\ComputerProperties;

use DB;

class ComputerTreeController extends Controller
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





    public function showSomeComputers(Request $request) 
    {
        try {
            if ($request->filled('name')) {
                $computerName = $request->get('name');
                if ($request->filled('computertargetid')) {
                    $computerTargetId = $request->get('computertargetid');
                    $computers = Computer::where('name', $computerName)->where('computertargetid', $computerTargetId)->orderBy('name')->get();
                } else {
                    $computers = Computer::where('name', $computerName)->orderBy('name')->get();
                }
            } else {
                if ($request->filled('computertargetid')) {
                    $computerTargetId = $request->get('computertargetid');
                    $computers = Computer::where('computertargetid', $computerTargetId)->orderBy('name')->get();
                } else {
                    $computers = Computer::query()->orderBy('name')->get();
                }
            }

            return response()->json($computers);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }

    }

    public function showAllPropertiesOfComputerDeviceTree($id)
    {
        try {
            $computer = Computer::findOrFail($id);
            $computerClasses = WmiClass::query()
                ->where('enabled', 1)
                ->where('name','NOT LIKE', 'Win32_Product')
                ->where('name','NOT LIKE', 'Win32_QuickFixEngineering')
                    ->get();

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




}
