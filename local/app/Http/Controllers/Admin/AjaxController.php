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

    public function categories(){
        $categories = \App\Category::all();
        return view('admin.objects.ajax_select_one',[
            'model'=>$categories,
            'type'=>'categories',
            'label'=>'دسته بندی'
        ]);
    }

    public function tags(){
        $tags = \App\Tag::all();
        return view('admin.objects.ajax_select_one',[
            'model'=>$tags,
            'type'=>'tags',
            'label'=>'تگ'
        ]);
    }

    public function publications(){
        $publications = \App\Publication::all();
        return view('admin.objects.ajax_select_one',[
            'model'=>$publications,
            'type'=>'publications',
            'label'=>'ناشر'
        ]);
    }

    public function writers(){
        $writers = \App\Author::where('type','writer')->get();
        return view('admin.objects.ajax_select_one',[
            'model'=>$writers,
            'type'=>'writers',
            'label'=>'نویسنده'
        ]);
    }

    public function translators(){
        $translators = \App\Author::where('type','translator')->get();
        return view('admin.objects.ajax_select_one',[
            'model'=>$translators,
            'type'=>'translators',
            'label'=>'مترجم'
        ]);
    }
}