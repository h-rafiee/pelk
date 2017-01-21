<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    function user(Request $request){
        $user_id = $request->user()->id;
        $data['status']='done';
        $data['user']= \App\User::with(['books','magazines'])->find($user_id);
        $data['books'] = \App\UserBook::where('user_id',$user_id)->get();
        $data['magazines'] = \App\UserMagazine::where('user_id',$user_id)->get();
        return response()->json($data);
    }

    function orders(Request $request){
        $user_id = $request->user()->id;
        $data['status']='done';
        $data['orders']=\App\Order::where('user_id',$user_id)->get();
        return response()->json($data);
    }

    function update(Request $request){
        $user_id = $request->user()->id;
        $user = \App\User::find($user_id);
        $user->name = $request->get('name');
        if($user->username != $request->get('username')){
            if(\App\User::where('username',$request->get('username'))->count()>0){
                $data['status']='fail';
                $data['message']='username exist';
                return response()->json($data);
            }
            $user->username = $request->get('username');
        }
        if($user->email != $request->get('email')){
            if(\App\User::where('email',$request->get('email'))->count()>0){
                $data['status']='fail';
                $data['message']='email exist';
                return response()->json($data);
            }
            $user->email = $request->get('email');
        }
        if(!empty($request->get('password'))){
            $user->password = \Hash::make($request->get('password'));
        }
        $user->mobile = (empty($request->get('mobile')))?'':$request->get('mobile');
        $user->save();
        $data['status']='done';
        $data['message']='update';
        return response()->json($data);
    }
}
