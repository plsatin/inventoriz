<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;


class WmiClassController extends Controller
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



    /**
     * @OA\Get(
     *     path="/api/v1/classes",
     *     description="Выборка WMI классов",
     *     tags={"classes"},
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает WMI классы",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/WmiClass"),
     *              ),
     *          ), 
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Ответ если WMI класс не найден",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function showAllWmiClasses(Request $request) 
    {
        try {
            $wmiClasses = WmiClass::all();

            return response()->json($wmiClasses);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }

    }


    public function showSomeWmiClasses(Request $request) 
    {
        try {
            $wmiClasses = WmiClass::all();

            return response()->json($wmiClasses);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }

    }


    /**
     * @OA\Get(
     *     path="/api/v1/classes/{id}",
     *     description="Выборка одного WMI класса",
     *     tags={"classes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ИД WMI класса",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает один WMI класс",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/WmiClass"),
     *              ),
     *          ), 
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Ответ если WMI класс не найден",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function showOneWmiClass($id)
    {
        try {
            return response()->json(WmiClass::findOrFail($id));
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }



/** Для этих функций маршруты временно отключены */

    public function create(Request $request)
    {
        try {

            $wmiClass = WmiClass::create($request->all());

            return response()->json($wmiClass, 201);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $wmiClass = WmiClass::findOrFail($id);
            $wmiClass->update($request->all());

            return response()->json($wmiClass, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }

    public function delete($id)
    {
        try {
            WmiClass::findOrFail($id)->delete();

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Deleted Successfully'));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }
    }





}
