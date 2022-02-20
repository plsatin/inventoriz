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
        $this->middleware('auth');
        $this->middleware('roles');
    }



    /**
     * @OA\Get(
     *     path="/api/v1/computers",
     *     description="Выборка компьютеров",
     *     tags={"computers"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Имя компьютера (можно частично)",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         name="computertargetid",
     *         in="query",
     *         description="GUID компьютера (точное соответствие)",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         name="start",
     *         in="query",
     *         description="Начало диапазона по ИД (по умолчанию 0)",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Ограничение диапазона по ИД (по умолчанию 10 000)",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Варианты сортировка: id, name (по умолчанию, если задан диапазон то по id, если нет то по name)",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает компьютеры",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Computer"),
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
    public function showSomeComputers(Request $request) 
    {
        try {

            // Ограничения выборки и сортировка
            if ($request->filled('start')) {
                $startOffset = $request->get('start');
                $orderBy = 'id';
            } else {
                $startOffset = 0;
                $orderBy = 'name';
            }

            if ($request->filled('limit')) {
                $limitOffset = $request->get('limit');
                $orderBy = 'id';
            } else {
                $limitOffset = 10000;
                $orderBy = 'name';
            }

            if ($request->filled('order')) {
                $orderBy = $request->get('order');
            }
            

            if ($request->filled('name')) {
                $computerName = $request->get('name');
                if ($request->filled('computertargetid')) {
                    $computerTargetId = $request->get('computertargetid');
                    $computers = Computer::where('name', 'like', '%' . $computerName . '%')->where('computertargetid', $computerTargetId)->skip($startOffset)->take($limitOffset)->orderBy($orderBy)->get();
                } else {
                    $computers = Computer::where('name', 'like', '%' . $computerName . '%')->skip($startOffset)->take($limitOffset)->orderBy($orderBy)->get();
                }
            } else {
                if ($request->filled('computertargetid')) {
                    $computerTargetId = $request->get('computertargetid');
                    $computers = Computer::where('computertargetid', $computerTargetId)->skip($startOffset)->take($limitOffset)->orderBy($orderBy)->get();
                } else {
                    $computers = Computer::query()->skip($startOffset)->take($limitOffset)->orderBy($orderBy)->get();
                }
            }

            return response()->json($computers);
        } catch (Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

    }



    /**
     * @OA\Get(
     *     path="/api/v1/computers/{id}",
     *     description="Получение компьютера по ИД",
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
     *         description="Возвращает свойства компьютера",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Computer"),
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
    public function showOneComputer($id)
    {
        try {
            return response()->json(Computer::findOrFail($id));
        } catch (Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }



    /**
     * @OA\Post(
     *     path="/api/v1/computers",
     *     description="Создание компьютера",
     *     tags={"computers"},
     *     @OA\RequestBody(
     *         description="Объект компьютер",
     *         required=true,
     *         @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(ref="#/components/schemas/Computer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Возвращает свойства компьютера",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Computer"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если компьютер не создан",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function create(Request $request)
    {
        try {

            $computer = Computer::create($request->all());

            return response()->json($computer, 201);
        } catch (Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/v1/computers/{id}",
     *     description="Обновление компьютера с ИД",
     *     tags={"computers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ИД компьютера",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Объект компьютер",
     *         required=true,
     *         @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(ref="#/components/schemas/Computer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает свойства компьютера",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Computer"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если предложение не найдено",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function update($id, Request $request)
    {
        try {
            $computer = Computer::findOrFail($id);
            // $computer->update($request->all());
            $computer->update([
                'last_inventory_start' => $request->input('last_inventory_start'),
                'last_inventory_end' => empty($request->input('last_inventory_end')) ? null : $request->input('last_inventory_end'),
                'last_inventory_index' => $request->input('last_inventory_index')
            ]);

            return response()->json($computer, 200);
        } catch (Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }



    /**
     * @OA\Delete(
     *     path="/api/v1/computers/{id}",
     *     description="Удаление компьютера с ИД",
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
     *         description="Ответ при успешном удалении",
     *          @OA\MediaType(
     *              mediaType="application/json",
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
    public function delete($id)
    {
        try {
            $computer = Computer::findOrFail($id);
            $computerName = $computer->name;
            $computer->delete();

            $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Computer ' . $computerName . ' deleted successfully'));
            return response()->json($responseObject, 200);
        } catch (Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }





}
