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




    /**
     * @OA\Schema(
     *      schema="ComputersInventoryByDate",
     *      type="array",
     *      @OA\Items(
     *          @OA\Property(
     *              property="date",
     *              type="string",
     *              description="Дата",
     *          ),
     *          @OA\Property(
     *              property="total",
     *              type="integer",
     *              description="Количество компьютеров",
     *          ),
     *      ),
     * )
     */




    /**
     * @OA\Get(
     *     path="/api/v1/reports/computers/last_updated",
     *     description="Получение количества инвентаризированных компьютеров с группировкой по дням",
     *     tags={"reports"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Ограничение диапазона дней (по умолчанию 7)",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает список компьютеров",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/ComputersInventoryByDate"),
     *          ),
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
    public function getComputersUpdatedAt(Request $request) 
    {
        try {

            // Лимит выборки дней
            if ($request->filled('limit')) {
                $limitOffset = $request->get('limit');
            } else {
                $limitOffset = 7;
            }

            /** SELECT updated_at, COUNT(id) AS qty  FROM computers GROUP BY DATE_FORMAT(updated_at , "%d-%m-%y") */

            $computers = Computer::select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as total'))
                ->groupBy('date')->orderBy('date', 'asc')->take($limitOffset)->get();


            return response()->json($computers);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

    }





    /**
     * @OA\Schema(
     *      schema="ComputersList",
     *      type="object",
     *      @OA\Property(
     *          property="draw",
     *          type="string",
     *          description="",
     *      ),
     *      @OA\Property(
     *          property="recordsTotal",
     *          type="string",
     *          description="Всего записей",
     *      ),
     *      @OA\Property(
     *          property="recordsFiltered",
     *          type="string",
     *          description="Выбрано записей",
     *      ),
     *      @OA\Property(
     *          property="data",
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(
     *                  property="NameWithLink",
     *                  type="string",
     *                  description="Имя компьютера в виде ссылки",
     *              ),
     *              @OA\Property(
     *                  property="InventoryDate",
     *                  type="string",
     *                  description="Дата последней инвентаризации",
     *              ),
     *              @OA\Property(
     *                  property="OperatingSystem",
     *                  type="string",
     *                  description="Операционная система",
     *              ),
     *              @OA\Property(
     *                  property="Processor",
     *                  type="string",
     *                  description="Процессор",
     *              ),
     *              @OA\Property(
     *                  property="TotalMemory",
     *                  type="string",
     *                  description="Оперативная память",
     *              ),
     *          ),
     *      ),
     * )
     */






    /**
     * @OA\Get(
     *     path="/api/v1/reports/computers/list",
     *     description="Получение списка компьютеров для статистической таблицы",
     *     tags={"reports"},
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
     *         description="Возвращает список компьютеров",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/ComputersList"),
     *          ),
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
    public function getComputersList(Request $request) 
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


            $totalComputers = Computer::count();
            $computers = Computer::query()->skip($startOffset)->take($limitOffset)->orderBy($orderBy)->get();

            $response = [];
            $data = [];
            $countComputers = 0;

            foreach ($computers as $computer) {

                $computerR = Computer::findOrFail($computer->id);

                $wmiproperty = WmiProperty::query()->findOrFail(4);
                $propertyCPU = ComputerProperties::query()->whereBelongsTo($computerR)->whereBelongsTo($wmiproperty)->get();
                $wmiproperty = WmiProperty::query()->findOrFail(15);
                $propertyOS = ComputerProperties::query()->whereBelongsTo($computerR)->whereBelongsTo($wmiproperty)->get();
                $wmiproperty = WmiProperty::query()->findOrFail(88);
                $propertyRAM = ComputerProperties::query()->whereBelongsTo($computerR)->whereBelongsTo($wmiproperty)->get();
    

                $arrComputer = ['<a href="/tree?computer=' . $computer->name . '">' . $computer->name . '</a>',
                    $computer->last_inventory_end,
                    $propertyOS[0]->value,
                    $propertyCPU[0]->value,
                    $propertyRAM[0]->value
                ];

                array_push($data, $arrComputer);
                $countComputers ++;
            }


            $response = ['draw' => 1,
                'recordsTotal' =>  $totalComputers,
                'recordsFiltered' => $countComputers,
                'data' => $data
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

    }




}
