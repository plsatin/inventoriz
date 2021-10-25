<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;
use App\Models\Computer;
use App\Models\ComputerProperties;


class ComputerController extends Controller
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
                    $computers = Computer::where('name', $computerName)->where('computertargetid', $computerTargetId)->get();
                } else {
                    $computers = Computer::where('name', $computerName)->get();
                }
            } else {
                if ($request->filled('computertargetid')) {
                    $computerTargetId = $request->get('computertargetid');
                    $computers = Computer::where('computertargetid', $computerTargetId)->get();
                } else {
                    $computers = Computer::all();
                }
            }

            return response()->json($computers);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }

    }

    public function showOneComputer($id)
    {
        try {
            return response()->json(Computer::findOrFail($id));
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }


    public function create(Request $request)
    {
        try {

            $computer = Computer::create($request->all());

            return response()->json($computer, 201);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $computer = Computer::findOrFail($id);
            $computer->update($request->all());

            return response()->json($computer, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function delete($id)
    {
        try {
            Computer::findOrFail($id)->delete();

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Deleted Successfully'));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }





}
