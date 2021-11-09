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
        $this->middleware('auth');
        $this->middleware('roles');
    }



    /**
     * @OA\Get(
     *     path="/api/v1/computers/{id}/properties",
     *     description="Выборка всех свойств компьютера",
     *     tags={"computers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ИД компьютера",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает все свойства компьютера",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ComputerProperties"),
     *              ),
     *          ), 
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если компьютер не найден",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function showAllPropertiesOfComputer($id)
    {
        try {
            $computer = Computer::findOrFail($id);
            $computerProperties = $computer->properties()->get();

            return response()->json($computerProperties, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' =>$e->getCode(),"Message"=>$e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }



    /**
     * @OA\Get(
     *     path="/api/v1/computers/{id}/properties/{class}/{property}",
     *     description="Запрос свойства компьютера",
     *     tags={"computers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ИД компьютера",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="class",
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
     *         description="Возвращает свойство компьютера",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ComputerProperties"),
     *              ),
     *          ), 
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если (компьютер, класс или свойство) не найдено",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function showOnePropertyOfComputer($id, $class, $property)
    {
        try {

            $computer = Computer::findOrFail($id);
            $wmiclass = WmiClass::findOrFail($class);
            $wmiproperty = WmiProperty::where('wmiclass_id', $wmiclass->id)->findOrFail($property);

            $property = ComputerProperties::query()->whereBelongsTo($computer)
                ->whereBelongsTo($wmiclass)
                    ->whereBelongsTo($wmiproperty)->get();

            return response()->json($property, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }




    /**
     * @OA\Get(
     *     path="/api/v1/computers/{id}/properties/{class}",
     *     description="Запрос свойств класса компьютера",
     *     tags={"computers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ИД компьютера",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="class",
     *         in="path",
     *         description="ИД WMI класса",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает свойства класса компьютера",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ComputerProperties"),
     *              ),
     *          ), 
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если (компьютер, класс) не найдено",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function showClassPropertiesOfComputer($id, $class, $property)
    {
        try {

            $computer = Computer::findOrFail($id);
            $wmiclass = WmiClass::findOrFail($class);

            $property = ComputerProperties::query()->whereBelongsTo($computer)
                ->whereBelongsTo($wmiclass)->get();

            return response()->json($property, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }



    /**
     * @OA\POST(
     *     path="/api/v1/computers/{id}/properties/{class}/{property}",
     *     description="Создание свойства компьютера",
     *     tags={"computers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ИД компьютера",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="class",
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
     *         response="201",
     *         description="Возвращает созданное свойство компьютера",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ComputerProperties"),
     *              ),
     *          ), 
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если (компьютер, класс или свойство) не найдено",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
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
                ['value' => trim($request->input('value'))]
            );


            return response()->json($property, 201);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }





    /**
     * @OA\PUT(
     *     path="/api/v1/computers/{id}/properties/{class}/{property}",
     *     description="Обновление свойства компьютера",
     *     tags={"computers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ИД компьютера",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="class",
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
     *         response="201",
     *         description="Сообщение об успешном создании свойства компьютера",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если (компьютер, класс или свойство) не найдено",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
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
            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Computer property ' . $wmiproperty->name . ' of WMI Class ' . $wmiclass->name . ' updated successfully'));
            return response()->json($responseObject, 201);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }




    /**
     * @OA\DELETE(
     *     path="/api/v1/computers/{id}/properties/{class}/{property}",
     *     description="Удаление свойства компьютера",
     *     tags={"computers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ИД компьютера",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="class",
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
     *         description="Сообщение об успешном удалении свойства компьютера",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если (компьютер, класс или свойство) не найдено",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function delete($id, $class, $property)
    {
        try {

            $computer = Computer::findOrFail($id);
            $wmiclass = WmiClass::findOrFail($class);
            $wmiproperty = WmiProperty::where('wmiclass_id', $wmiclass->id)->findOrFail($property);

            $property = ComputerProperties::whereBelongsTo($computer)
                ->whereBelongsTo($wmiclass)
                    ->whereBelongsTo($wmiproperty)->delete();

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Computer property ' . $wmiproperty->name . ' of WMI Class ' . $wmiclass->name . ' deleted Successfully'));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }




    /**
     * @OA\DELETE(
     *     path="/api/v1/computers/{id}/classes/{class}",
     *     description="Удаление класса свойств компьютера",
     *     tags={"computers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ИД компьютера",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="class",
     *         in="path",
     *         description="ИД WMI класса",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Сообщение об успешном удалении класса свойств компьютера",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если (компьютер, класс или свойство) не найдено",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function deleteWmiClass($id, $class)
    {
        try {

            $computer = Computer::findOrFail($id);
            $wmiclass = WmiClass::findOrFail($class);

            $property = ComputerProperties::whereBelongsTo($computer)
                ->whereBelongsTo($wmiclass)->delete();

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Computer properties of WMI Class ' . $wmiclass->name . ' deleted successfully'));
            return response()->json($responseObject, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }




}
