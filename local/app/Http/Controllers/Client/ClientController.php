<?php

namespace App\Http\Controllers\Client;

use Faker\Provider\zh_TW\DateTime;
use Illuminate\Http\Request;
use File;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    function getHome(){
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
            $template = parse_ini_file(app_path('webtemp.ini'),true);
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

    function getProfile(){
        return view('profile',[
            'user_nav'=>'active'
        ]);
    }

    function getSearch(){
        return view('search',[
            'search_nav'=>'active'
        ]);
    }
}
