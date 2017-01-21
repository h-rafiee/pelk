<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DownloadController extends Controller
{
    function book(Request $request , $id , $demo='F'){
        $user_id = $request->user()->id;
        $demo = ($demo=='T')?1:0;
        $item = \App\UserBook::with(['book'])
                    ->where('user_id',$user_id)
                    ->where('book_id',$id)
                    ->where('demo',$demo)
                    ->first();
        if(empty($item)){
            $data['status']='fail';
            $data['message']='not item found';
            return response()->json($data);
        }
        $file = ($demo==0)?$item->book->file:$item->book->file_demo;
        $file = public_path($file);
        return response()->download($file);
    }

    function magazine(Request $request , $id , $demo='F'){
        $user_id = $request->user()->id;
        $demo = ($demo=='T')?1:0;
        $item = \App\UserMagazine::with(['magazine'])
            ->where('user_id',$user_id)
            ->where('magazine_id',$id)
            ->where('demo',$demo)
            ->first();
        if(empty($item)){
            $data['status']='fail';
            $data['message']='not item found';
            return response()->json($data);
        }
        $file = ($demo==0)?$item->magazine->file:$item->magazine->file_demo;
        $file = public_path($file);
        return response()->download($file);
    }
}
