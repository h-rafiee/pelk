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

    public function getWebTemplate(){
        $template = parse_ini_file(app_path('webtemp.ini'),true);
        $data = null;
        foreach($template as $key => $value){
            switch($value['type']){
                case 'categories':
                    $data['categories-label'] = 'دسته بندی';
                    if(!array_key_exists('categories',$data))
                        $data['categories'] = \App\Category::all();
                    break;
                case 'tags' :
                    $data['tags-label'] = 'تگ';
                    if(!array_key_exists('tags',$data))
                        $data['tags'] = \App\Tag::all();
                    break;
                case 'publications' :
                    $data['publications-label'] = 'ناشر';
                    if(!array_key_exists('publications',$data))
                        $data['publications'] = \App\Publication::all();
                    break;
                case 'writers' :
                    $data['writers-label'] = 'نویسنده';
                    if(!array_key_exists('writers',$data))
                        $data['writers'] = \App\Author::where('type','writer')->get();
                    break;
                case 'translators' :
                    $data['translators-label'] = 'مترجم';
                    if(!array_key_exists('translators',$data))
                        $data['translators'] = \App\Author::where('type','translator')->get();
                    break;
            }
        }

        return view('admin.web-template',['template'=>$template,'data'=>$data]);
    }

    public function postWebTemplate(Request $request){
        $items = $request->item;
        $ordered_items = array();
        $i = 0 ;
        foreach($items['value'] as $key => $value){
            $ordered_items['item-'.$i]['value']= $value;
            $ordered_items['item-'.$i]['type']= $items['type'][$key];
            $i++;
        }
        $helper = new \App\Helpers\Helper();
        $helper->write_ini_file($ordered_items,app_path('webtemp.ini'),true);
        return redirect('admin/web/template')->with('success', 'تنظیمات قالب ذخیره شد.');
    }
}