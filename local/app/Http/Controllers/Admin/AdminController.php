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



    public function getMobileTemplate(){
        $template = parse_ini_file(app_path('mobiletemp.ini'),true);
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

        return view('admin.mob-template',['template'=>$template,'data'=>$data]);
    }

    public function postMobileTemplate(Request $request){
        $items = $request->item;
        $ordered_items = array();
        $i = 0 ;
        $path = 'uploads/m/banner';
        foreach($items['value'] as $key => $value){
            $ordered_items['item-'.$i]['banner']='no';
            $ordered_items['item-'.$i]['file'] = 'none';
            $ordered_items['item-'.$i]['value']= $value;
            $ordered_items['item-'.$i]['type']= $items['type'][$key];
            if(@$items['has-banner'][$key] == 'yes'){
                $ordered_items['item-'.$i]['banner']='yes';
                if(!empty($items['banner-file'][$key]) && $items['banner-file'][$key] !="empty"){
                    $file = $items['banner-file'][$key];
                }else{
                    $file = $this->do_upload($items['banner'][0],$path);
                    array_splice($items['banner'], 0, 1);
                }
                $ordered_items['item-'.$i]['file'] = $file;

            }
            $i++;
        }
        $helper = new \App\Helpers\Helper();
        $helper->write_ini_file($ordered_items,app_path('mobiletemp.ini'),true);
        return redirect('admin/mobile/template')->with('success', 'تنظیمات قالب ذخیره شد.');
    }

    public function getMobileSliders(){
        $template = parse_ini_file(app_path('mobilesliders.ini'),true);
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

        return view('admin.mob-sliders',['template'=>$template,'data'=>$data]);
    }

    public function postMobileSliders(Request $request){
        $items = $request->item;
        $ordered_items = array();
        $i = 0 ;
        $path = 'uploads/m/sliders';
        foreach($items['value'] as $key => $value){
            $ordered_items['item-'.$i]['banner']='no';
            $ordered_items['item-'.$i]['file'] = 'none';
            $ordered_items['item-'.$i]['value']= $value;
            $ordered_items['item-'.$i]['type']= $items['type'][$key];
            if(@$items['has-banner'][$key] == 'yes'){
                $ordered_items['item-'.$i]['banner']='yes';
                if(!empty($items['banner-file'][$key]) && $items['banner-file'][$key] !="empty"){
                    $file = $items['banner-file'][$key];
                }else{
                    $file = $this->do_upload($items['banner'][0],$path);
                    array_splice($items['banner'], 0, 1);
                }
                $ordered_items['item-'.$i]['file'] = $file;

            }
            $i++;
        }
        $helper = new \App\Helpers\Helper();
        $helper->write_ini_file($ordered_items,app_path('mobilesliders.ini'),true);
        return redirect('admin/mobile/sliders')->with('success', 'تنظیمات اسلایدرها ذخیره شد.');
    }



    private function do_upload($file,$path='uploads'){
        $fileURL=null;
        $desPath = $path;
        if(!is_dir($desPath))
            mkdir($desPath,0775,true);
        $rand = $this->generateRandomString(30);
        $fileName = $rand.".".$file->getClientOriginalExtension();
        while(file_exists($desPath.'/'.$fileName) == true){
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$file->getClientOriginalExtension();
        }
        if($file->move($desPath, $fileName)){
            $fileURL = $desPath.'/'.$fileName;
        }
        return $fileURL;
    }

    private function generateRandomString($length = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
}