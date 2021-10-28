<?php
namespace App\Http\Controllers;

use Response;
use View;
use File;

class JsonFileController extends Controller
{

	public function jsonFileDownload()
    {
        $data = json_encode(['Text One', 'Text Two', 'Text Three']);









        $jsongFile = time() . '_file.json';
        File::put(public_path('/upload/json/'.$jsongFile), $data);
        return Response::download(public_path('/upload/jsonfile/'.$jsongFile));
	}




}