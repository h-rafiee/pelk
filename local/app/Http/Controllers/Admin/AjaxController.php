<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
    public function upload(Request $request){
        $url = null;
        if ($request->hasFile('file')) {
            $desPath = "uploads/tmp/".date("Ymd");
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = str_random(10).time();
            $fileName = $rand.".".$request->file('file')->getClientOriginalExtension();
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = str_random(10).time();
                $fileName = $rand.".".$request->file('file')->getClientOriginalExtension();
            }
            if($request->file('file')->move($desPath, $fileName)){
                $url = $desPath.'/'.$fileName;
            }
        }
        if(empty($url)){
            abort(500);
        }
        echo $url;
        return;
    }
}