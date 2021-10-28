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

	public function jsonFileDownload()
    {
        $wmiClasses = json_encode(WmiClass::all());



        $jsongFile = time() . '_file.json';
        File::put($this->public_path('/upload/json/'.$jsongFile), $wmiClasses);
        return Response::download(public_path('/upload/jsonfile/'.$jsongFile));
	}




    

    public function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }


}