<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\WmiClass;
use App\Models\WmiProperty;
use App\Models\Computer;
use App\Models\ComputerProperties;

use DB;
use Exception;


class SoftwareController extends Controller
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




    public function createSoftwaresTable(Request $request)
    {
        try {

            Schema::create('tmp_softwares', function($table)
            {
                $table->increments('id');
                $table->integer('computer_id');
                $table->string('name');
                $table->string('version');
                $table->string('vendor');
                $table->string('install_date');
                $table->string('identifying_number');
                $table->timestamps();
            });



            $totalComputers = Computer::count();
            $computers = Computer::query()->skip($startOffset)->take($limitOffset)->orderBy('id')->get();

            $response = [];
            $data = [];
            $countComputers = 0;
            $totalSoft = 0;

            $wmiproperty901 = WmiProperty::query()->findOrFail(901);
            $wmiproperty902 = WmiProperty::query()->findOrFail(902);
            $wmiproperty903 = WmiProperty::query()->findOrFail(903);
            $wmiproperty904 = WmiProperty::query()->findOrFail(904);
            $wmiproperty905 = WmiProperty::query()->findOrFail(905);


            foreach ($computers as $computer) {

                $computerR = Computer::findOrFail($computer->id);

                $countSoft = 0;

                $Name = ComputerProperties::query()->whereBelongsTo($computerR)->whereBelongsTo($wmiproperty901)->get();
                $Version = ComputerProperties::query()->whereBelongsTo($computerR)->whereBelongsTo($wmiproperty902)->get();
                $Vendor = ComputerProperties::query()->whereBelongsTo($computerR)->whereBelongsTo($wmiproperty903)->get();
                $InstallDate = ComputerProperties::query()->whereBelongsTo($computerR)->whereBelongsTo($wmiproperty904)->get();
                $IdentifyingNumber = ComputerProperties::query()->whereBelongsTo($computerR)->whereBelongsTo($wmiproperty905)->get();
                

                $computerSoftCount = count($Name);

                for ($i = 0; $i < $computerSoftCount; $i++) {

                    if ($Name[$i]->value != '') {
                        $arrSoftwares = [$computerR->name,
                            $Name[$i]->value,
                            $Version[$i]->value,
                            $Vendor[$i]->value,
                            $InstallDate[$i]->value,
                            $IdentifyingNumber[$i]->value
                        ];

                        array_push($data, $arrSoftwares);

                        $dataToTable = array(
                            'computer_id' => $computerR->id,
                            'name' => $Name[$i]->value,
                            'version' => $Version[$i]->value,
                            'vendor' => $Vendor[$i]->value,
                            'install_date' => $InstallDate[$i]->value,
                            'identifying_number' => $IdentifyingNumber[$i]->value
                        );
            
                        DB::table('tmp_softwares')->insert($dataToTable);        

                        // $countSoft ++;
                        $totalSoft ++;

                    }
                }

                $countComputers ++;
            }


            return response()->json($data, 200);
        } catch (Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject);
        }

    }




}