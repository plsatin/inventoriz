<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;
use App\Models\Computer;
use App\Models\ComputerProperties;

use DB;

class HomeController extends Controller
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




    
    
    /**
     * Домашняя страница
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        try {
            $page_title = 'Статистика';
            return view('index')->with('page_title', $page_title);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

    }


    public function getApiVersion()
    {
        try {
            $apiVersionTxt = 'API Version 0.9.2';

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => $apiVersionTxt));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }


    public function getApiRoutes()
    {
        try {

            $app = app();
            $routes = $app->routes->getRoutes();
            $page_title = 'Маршруты';
           
            return view ('routes');
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }




    /**
     * Диспетчер устройств в виде дерева
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showComputerTree(Request $request)
    {
        try {
            if ($request->input('computer') != '') {
                $computerName = $request->input('computer');
                $computer = Computer::query()->where('name', $computerName)->firstOrFail();
        
                $page_title = 'Инвентаризация: ' . $computer->name;
                return view('computers.tree')->withComputer($computer)->with('page_title', $page_title);
            } else {
                $page_title = 'Инвентаризация';
                return view('computers.tree-list')->with('page_title', $page_title);
            }
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

    }




}
