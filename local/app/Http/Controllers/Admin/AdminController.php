<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Crypt;

class AdminController extends Controller
{
    public function getLogin(){
        return view('admin.login');
    }
    public function postLogin(Request $request){
        $username_email = strtolower($request->get('username'));
        $password = $request->get('password');
        $admin = \App\Administrator::where('username',$username_email)->orWhere('email',$username_email)->orWhere('mobile',$username_email)->first();
        if(!empty($admin)){
            if(\Hash::check($password,$admin->password)){
                $request->session()->set('admin_authentication', Crypt::encrypt($admin->id.'.'.time()));
                return redirect('admin');
            }
        }
        return redirect('admin-login')->withErrors(['login'=>'شما به پنل مدیریت دسترسی ندارید']);
    }

    public function logout(Request $request){
        $request->session()->forget('admin_authentication');
        return redirect('admin');
    }
}