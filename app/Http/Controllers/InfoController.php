<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;
use App\Models\Computer;
use App\Models\ComputerProperties;


class InfoController extends Controller
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


    public function apiVersion()
    {
        try {
            $apiVersionTxt = 'API Version 0.9';

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => $apiVersionTxt));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }


    /**
     * Диспетчер устройств в виде дерева
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showComputerTree(Request $request)
    {
        $page_title = 'Добро пожаловать!';
        return view('computers.tree')>with('page_title', $page_title);
    }





}
