<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WmiClass;
use App\Models\WmiProperty;


class WmiPropertyController extends Controller
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
     *     path="/api/v1/classes/{id}/properties",
     *     description="Выборка WMI классов",
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
     *         description="Возвращает свойства WMI класса",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/WmiProperty"),
     *              ),
     *          ), 
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если свойства не найдены",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function showAllPropertiesOfWmiClass($id)
    {
        try {

            $wmiClass = WmiClass::findOrFail($id);
            // $classProperties = WmiProperty::query()->where('wmiclass_id', $wmiClass->id)->get();
            $classProperties = $wmiClass->properties()->get();


            return response()->json($classProperties, 200);
        } catch (Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' =>$e->getCode(),"Message"=>$e->getMessage()));
            return response()->json($responseObject);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/classes/{id}/properties/{property}",
     *     description="Выборка WMI классов",
     *     tags={"classes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ИД WMI класса",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="property",
     *         in="path",
     *         description="ИД свойства WMI класса",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает свойство WMI класса",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/WmiProperty"),
     *          ), 
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если свойства не найдены",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function showOnePropertyOfWmiClass($id, $property)
    {
        try {
            $wmiClass = WmiClass::findOrFail($id);
            $classProperty = WmiProperty::where('wmiclass_id', $wmiClass->id)->findOrFail($property);

            return response()->json($classProperty, 200);
        } catch (Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' =>$e->getCode(),"Message"=>$e->getMessage()));
            return response()->json($responseObject);
        }
    }



/* Для этих функций маршруты отключены */

    public function create(Request $request, $id)
    {
        try {
            $wmiClass = WmiClass::findOrFail($id);
            $wmiProperty = $wmiClass->properties()->create($request->all());

            return response()->json($wmiProperty, 201);
        } catch (Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject);
        }
    }

    public function update(Request $request, $id, $property)
    {
        try {
            $wmiClass = WmiClass::findOrFail($id);
            $wmiProperty = $wmiClass->properties()->findOrFail($property);
            $wmiProperty->update($request->all());

            return response()->json($wmiProperty, 200);
        } catch (Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject);
        }
    }

    public function delete($id, $property)
    {
        try {
            $wmiClass = WmiClass::findOrFail($id);
            $wmiProperty = $wmiClass->properties()->findOrFail($property);
            $wmiProperty->delete();

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Deleted Successfully'));
            return response()->json($responseObject, 200);
        } catch (Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject);
        }
    }



}
