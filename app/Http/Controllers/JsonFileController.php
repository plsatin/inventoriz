<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

use Response;
use View;

use App\Models\WmiClass;
use App\Models\WmiProperty;



class JsonFileController extends Controller
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


    public function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }




    /**
     * @OA\Get(
     *     path="/api/v1/downloads/classes-json",
     *     description="Возвращает json-файл с описанием классов",
     *     tags={"json-files"},
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает json-файл с описанием классов",
     *         @OA\Schema(type="file")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если что-то пошло не так",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function classesJsonFileDownload()
    {
        try {
            $wmiClasses = json_encode(WmiClass::all());

            $jsonFile = time() . '-classes.json';
            File::put($this->public_path('uploads/'.$jsonFile), $wmiClasses);
            return response()->download($this->public_path('uploads/'.$jsonFile));
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }



    /**
     * @OA\Get(
     *     path="/api/v1/downloads/properties-json",
     *     description="Возвращает json-файл с описанием свойств классов",
     *     tags={"json-files"},
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает json-файл с описанием свойств классов",
     *         @OA\Schema(type="file")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если что-то пошло не так",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */
    public function propertiesJsonFileDownload()
    {
        try {
            // $id = 0;
            // $wmiClass = WmiClass::findOrFail($id);
            // // $classProperties = WmiProperty::query()->where('wmiclass_id', $wmiClass->id)->get();
            // $classProperties = $wmiClass->properties()->get();

            $wmiProperties = json_encode(WmiProperty::all());


            $jsonFile = time() . '-properties.json';
            File::put($this->public_path('uploads/'.$jsonFile), $wmiProperties);
            return response()->download($this->public_path('uploads/'.$jsonFile));

        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }







}