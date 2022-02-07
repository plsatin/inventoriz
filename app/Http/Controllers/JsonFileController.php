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
        // $this->middleware('auth');
        // $this->middleware('roles');
    }


    public function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }




    public function classesJsonFileDownload()
    {
        try {
            $wmiClasses = json_encode(WmiClass::all());

            $jsonFile = time() . '_file.json';
            File::put($this->public_path('uploads/'.$jsonFile), $wmiClasses);
            return response()->download($this->public_path('uploads/'.$jsonFile));
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }


    public function propertiesJsonFileDownload()
    {
        try {
            // $id = 0;
            // $wmiClass = WmiClass::findOrFail($id);
            // // $classProperties = WmiProperty::query()->where('wmiclass_id', $wmiClass->id)->get();
            // $classProperties = $wmiClass->properties()->get();

            $wmiProperties = json_encode(WmiProperty::all());


            $jsonFile = time() . '_file.json';
            File::put($this->public_path('uploads/'.$jsonFile), $wmiProperties);
            return response()->download($this->public_path('uploads/'.$jsonFile));

        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }
    }







}