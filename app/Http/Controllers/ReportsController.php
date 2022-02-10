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
            
            // $wmiclass = WmiClass::findOrFail($property->wmiclass_id);
            // $wmiproperty = WmiProperty::where('wmiclass_id', $wmiclass->id)->findOrFail($property);

            $property = ComputerProperties::select('computer_id', 'value')->where('wmiproperty_id', $property)->orderBy('value')->orderBy('computer_id')->get();



            // $computers = ComputerProperties::select();

            return response()->json($property);
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
