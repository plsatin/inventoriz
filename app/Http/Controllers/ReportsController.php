<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;
use App\Models\Computer;
use App\Models\ComputerProperties;

use DB;

class ReportsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('roles');
    }





    public function getComputersProperty(Request $request, $property) 
    {
        try {
            $property = ComputerProperties::select('computer_id', 'value')->where('wmiproperty_id', $property)->orderBy('value')->orderBy('computer_id')->get();

            return response()->json($property);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

    }





    public function getComputersUpdatedAt(Request $request) 
    {
        try {
            /** SELECT updated_at, COUNT(id) AS qty  FROM computers GROUP BY DATE_FORMAT(updated_at , "%d-%m-%y") */

            $computers = Computer::select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as total'))
                ->groupBy('date')->orderBy('date', 'asc')->take(7)->get();


            return response()->json($computers);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

    }


    public function getComputersTable(Request $request) 
    {
        try {
            /** SELECT updated_at, COUNT(id) AS qty  FROM computers GROUP BY DATE_FORMAT(updated_at , "%d-%m-%y") */

            $computers = Computer::select('name', 'last_inventory_end', 'computertargetid', 'id', 'last_inventory_index')->orderBy('name', 'asc')->get();


            $response = [];
            $data = [];

            $countComputers = 0;

            foreach ($computer as $computers) {
                $arrComputer = [$computer->name,
                    $computer->last_inventory_end,
                    $computer->computertargetid,
                    $computer->id,
                    $computer->last_inventory_index,
                ];

                array_push($data, $arrComputer);
                $countComputers ++;
            }


            $response = ['draw' => 1,
                'recordsTotal' =>  $countComputers,
                'recordsFiltered' => $countComputers,
                'data' => $data
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

    }


    
    
    /**
     * Диаграммы
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showCharts(Request $request)
    {
        try {
            $page_title = 'Статистика';
            return view('reports.charts')->with('page_title', $page_title);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

    }






}
