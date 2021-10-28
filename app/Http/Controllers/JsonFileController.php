<?php
namespace App\Http\Controllers;

use Response;
use View;
use File;

class JsonFileController extends Controller
{

	public function jsonFileDownload()
    {
        $wmiClasses = json_encode(WmiClass::all());



        $jsongFile = time() . '_file.json';
        File::put(public_path('/upload/json/'.$jsongFile), $wmiClasses);
        return Response::download(public_path('/upload/jsonfile/'.$jsongFile));
	}




}