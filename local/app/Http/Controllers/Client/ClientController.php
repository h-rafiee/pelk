<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use File;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Crypt;

class ClientController extends Controller
{
    function getHome(){
        unlink(resource_path('views/datas/home.data.json'));
        if(file_exists(resource_path('views/datas/home.data.json'))){
            $file_date = date("Y-m-d H:i:s",filemtime(resource_path('views/datas/home.data.json')));
            $file_date = date_create($file_date);
            $today_date = date("Y-m-d H:i:s");
            $today_date = date_create($today_date);
            $interval = date_diff($file_date, $today_date);
            if($interval->d >= 7 ){
                unlink(resource_path('views/datas/home.data.json'));
            }
        }
        if(!file_exists(resource_path('views/datas/home.data.json'))){
            $template = parse_ini_file(storage_path('template/webtemp.ini'),true);
            $data = null;
            $limit = 5;
            $offset = 0;
            $i = 0 ;
            foreach($template as $key => $value){
                $id = $value['value'];
                switch($value['type']){
                    case 'categories' :
                        $data['items'][$i]['type'] = $value['type'];
                        $data['items'][$i]['model'] = \App\Category::with(['books_four','magazines_four'])
                                                        ->where('id',$id)->first();
                        break;
                    case 'tags' :
                        $data['items'][$i]['type'] = $value['type'];
                        $data['items'][$i]['model'] = \App\Tag::with(['books_four','magazines_four'])
                            ->where('id',$id)->first();
                        break;

                    case 'publications' :
                        $data['items'][$i]['type'] = $value['type'];
                        $data['items'][$i]['model'] = \App\Publication::with(['books_four','magazines_four'])
                            ->where('id',$id)->first();
                        break;

                    case 'writers' :
                        $data['items'][$i]['type'] = $value['type'];
                        $data['items'][$i]['model'] = \App\Author::with(['wbooks_four'])
                            ->where('type','writer')->where('id',$id)->first();
                        break;

                    case 'translators' :
                        $data['items'][$i]['type'] = $value['type'];
                        $data['items'][$i]['model'] = \App\Author::with(['tbooks_four'])
                            ->where('type','translator')->where('id',$id)->first();
                        break;
                }
                $i++;
            }
            file_put_contents(resource_path('views/datas/home.data.json'),json_encode($data));
        }
        $books = collect(\App\Book::where('active',1)->orderBy('created_at','DESC')->get());
        $magazines = collect(\App\Magazine::where('active',1)->orderBy('created_at','DESC')->get());
        $home['last'] = $books->merge($magazines)->sortByDesc('created_at')->take(4);
        $home['data'] = json_decode(file_get_contents(resource_path('views/datas/home.data.json')));
        $home['slider'] = \App\Promote::orderBy('position','DESC')->get();
        return view('home',$home);
    }

    function getIntro(){
        return view('intro');
    }

    function getCategories(){
        $categories = \App\Category::with(['magazines_four','books_four'])->get();
        $data = [
            'cat_nav'=>'active',
            'categories'=>$categories
        ];
        return view('categories',$data);
    }

    function getLogin(){
        return view('login',[
            'user_nav'=>'active'
        ]);
    }

    function postLogin(Request $request){
        $email = $request->email;
        $password = $request->password;
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect('profile');
        }
        return redirect('login');
    }

    function getProfile(){
        $user_id = Auth::user()->id;
        $user = \App\User::find($user_id);
        $books = \App\UserBook::with('book')->where('user_id',$user_id)->get();
        $magazines = \App\UserMagazine::with('magazine')->where('user_id',$user_id)->get();
        return view('profile',[
            'user_nav'=>'active',
            'user'=>$user,
            'books'=>$books,
            'magazines'=>$magazines
        ]);
    }

    function editProfile(Request $request){
        $user_id = Auth::user()->id;
        $user = \App\User::find($user_id);
        $user->name = $request->get('name');
        if(!empty($request->get('username')) && $user->username != $request->get('username')){
            if(\App\User::where('username',$request->get('username'))->count()>0){
                $data['status']='fail';
                $data['message']='username exist';
                return redirect('profile')->with($data);
            }
            $user->username = $request->get('username');
        }
        if($user->email != $request->get('email')){
            if(\App\User::where('email',$request->get('email'))->count()>0){
                $data['status']='fail';
                $data['message']='email exist';
                return redirect('profile')->with($data);
            }
            $user->email = $request->get('email');
        }
        $file = $request->file('uploader');
        $fileURL = null;
        if (!empty($file)) {
            $extension = last(explode(".",$file));
            $desPath = "uploads/users/".hash('adler32',$user_id);
            if(!is_dir($desPath))
                mkdir($desPath,0775,true);
            $rand = $this->generateRandomString(30);
            $fileName = $rand.".".$extension;
            while(file_exists($desPath.'/'.$fileName) == true){
                $rand = $this->generateRandomString(30);
                $fileName = $rand.".".$extension;
            }
            if(rename($file,$desPath.'/'.$fileName)){
                $fileURL = $desPath.'/'.$fileName;
            }
        }
        if(!empty($request->get('password'))){
            $user->password = \Hash::make($request->get('password'));
        }
        if(!empty($fileURL)){
            if(!empty($user->profile_pic) && file_exists(public_path($user->profile_pic)))
                unlink(public_path($user->profile_pic));
            $user->profile_pic = $fileURL;
        }
        $user->mobile = (empty($request->get('mobile')))?'':$request->get('mobile');
        $user->save();
        $data['status']='done';
        $data['message']='update';
        return redirect('profile')->with($data);
    }

    function getSearch(){
        return view('search',[
            'search_nav'=>'active'
        ]);
    }

    private function generateRandomString($length = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
}
