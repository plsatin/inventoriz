<?php
namespace App\Http\Controllers;

use App\Models\WmiClass;
use App\Models\WmiProperty;

use Response;
use View;
// use File;

use Illuminate\Support\Facades\File;


class JsonFileController extends Controller
{

    public function classesJsonFileDownload()
    {
        $wmiClasses = json_encode(WmiClass::all());

        $jsongFile = time() . '_file.json';
        File::put($this->public_path('uploads/'.$jsongFile), $wmiClasses);
        return response()->download($this->public_path('uploads/'.$jsongFile));
    }


    public function propertiesJsonFileDownload()
    {
        try {
            $id = 0;

            $wmiClass = WmiClass::findOrFail($id);
            // $classProperties = WmiProperty::query()->where('wmiclass_id', $wmiClass->id)->get();
            $classProperties = $wmiClass->properties()->get();


            // $jsongFile = time() . '_file.json';
            // File::put($this->public_path('uploads/'.$jsongFile), $classProperties);
            // return response()->download($this->public_path('uploads/'.$jsongFile));

        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 404);
        }

    }





    public function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }


}