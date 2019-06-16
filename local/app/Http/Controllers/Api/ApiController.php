<?php

namespace App\Http\Controllers\Api;

use App\Book;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;

class ApiController extends Controller
{
    public function home(){
        unlink(resource_path('views/datas/home.api.data.json'));
        if(file_exists(resource_path('views/datas/home.api.data.json'))){
            $file_date = date("Y-m-d H:i:s",filemtime(resource_path('views/datas/home.api.data.json')));
            $file_date = date_create($file_date);
            $today_date = date("Y-m-d H:i:s");
            $today_date = date_create($today_date);
            $interval = date_diff($file_date, $today_date);
            if($interval->d >= 7 ){
                unlink(resource_path('views/datas/home.api.data.json'));
            }
        }
        if(!file_exists(resource_path('views/datas/home.api.data.json'))|| true){
            $template = parse_ini_file(storage_path('template/mobiletemp.ini'),true);
            $slider = parse_ini_file(storage_path('template/mobilesliders.ini'),true);

            $data = null;
            $books = \App\Book::with(['translators','writers','publication','tags','category'])->where('active',1)->orderBy('created_at','DESC')->take(10)->get()->toArray();
            $magazines = \App\Magazine::with(['publication','tags','category'])->where('active',1)->orderBy('created_at','DESC')->take(10)->get()->toArray();
            $data['newest']['books'] = $books;
            $data['newest']['magazines'] = $magazines;
            $limit = 5;
            $offset = 0;
            $i = 0 ;
            foreach($slider as $skey => $sval){
                $data['sliders'][$i]['file']= $sval['file'];
                $data['sliders'][$i]['banner']= $sval['banner'];
                $data['sliders'][$i]['id']= $sval['value'];
                $data['sliders'][$i]['type']= $sval['type'];
                $i++;
            }
            $i = 0 ;
            foreach($template as $key => $value){
                $id = $value['value'];
                $data['items'][$i]['file'] = $value['file'];
                $data['items'][$i]['banner'] = $value['banner'];
                switch($value['type']){
                    case 'categories' :
                        $data['items'][$i]['type'] = $value['type'];
                        $data['items'][$i]['model'] = \App\Category::with(['books_ten','magazines_ten'])
                            ->where('id',$id)->first();
                        break;
                    case 'tags' :
                        $data['items'][$i]['type'] = $value['type'];
                        $data['items'][$i]['model'] = \App\Tag::with(['books_ten','magazines_ten'])
                            ->where('id',$id)->first();
                        break;

                    case 'publications' :
                        $data['items'][$i]['type'] = $value['type'];
                        $data['items'][$i]['model'] = \App\Publication::with(['books_ten','magazines_ten'])
                            ->where('id',$id)->first();
                        break;

                    case 'writers' :
                        $data['items'][$i]['type'] = $value['type'];
                        $data['items'][$i]['model'] = \App\Author::with(['wbooks_ten'])
                            ->where('type','writer')->where('id',$id)->first();
                        break;

                    case 'translators' :
                        $data['items'][$i]['type'] = $value['type'];
                        $data['items'][$i]['model'] = \App\Author::with(['tbooks_ten'])
                            ->where('type','translator')->where('id',$id)->first();
                        break;
                }
                $i++;
            }
            file_put_contents(resource_path('views/datas/home.api.data.json'),json_encode($data));
        }
        $home = file_get_contents(resource_path('views/datas/home.api.data.json'));
        $home = json_Decode($home);
        return response()->json($home);
        
    }
    public function sign_up(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password'=>'required|min:5',
        ]);
        $user = new \App\User();
        $user->name = $request->get('name');
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = \Hash::make($request->get('password'));
        $user->mobile = (empty($request->get('mobile')))?'':$request->get('mobile');
        $user->save();
        $data['status'] = 'done';
        $data['message'] = 'User sign up successfully !';
        return response()->json($data);
    }

    public function categories(){
        $data['status'] = 'done';
        $data['categories'] = \App\Category::all();
        return response()->json($data);
    }

    public function publication($id,$type='profile',$page=1){
        $data['status'] = 'done';
        $data['publication'] = \App\Publication::find($id);
        $limit = 10 ;
        $offset = ($page - 1)*$limit;
        switch($type){
            case "profile":
                $data['books'] = \App\Book::whereHas('publication',function($q) use ($id){
                    $q->where('id',$id);
                })->where('active',1)->orderBy('created_at','DESC')->skip(0)->take(5)->get();
                $data['magazines'] = \App\Magazine::whereHas('publication',function($q) use ($id){
                    $q->where('id',$id);
                })->where('active',1)->orderBy('created_at','DESC')->skip(0)->take(5)->get();
                break;
            case "books" :
                $data['books'] = \App\Book::whereHas('publication',function($q) use ($id){
                    $q->where('id',$id);
                })->where('active',1)->orderBy('created_at','DESC')->skip($offset)->take($limit)->get();
                break;

            case "magazines" :
                $data['magazines'] = \App\Magazine::whereHas('publication',function($q) use ($id){
                    $q->where('id',$id);
                })->where('active',1)->orderBy('created_at','DESC')->skip($offset)->take($limit)->get();
                break;

            default:
                break;
        }
        return response()->json($data);
    }


    public function writer($id,$page=1){
        $data['status'] = 'done';
        $data['writer'] = \App\Author::where('type','writer')->where('id',$id)->first();
        if(empty($data['writer'])){
            $data['status']='fail';
            $data['message']='not valid writer';
            return response()->json($data);
        }
        $limit = 5 ;
        $offset = ($page - 1)*$limit;
        $data['books'] = \App\Book::whereHas('writers',function($q) use ($id){
                              $q->where('id',$id);
                            })->where('active',1)->orderBy('created_at','DESC')
                            ->skip($offset)->take($limit)->get();
        return response()->json($data);
    }

    public function translator($id,$page=1){
        $data['status'] = 'done';
        $data['translator'] = \App\Author::where('type','translator')->where('id',$id)->first();
        if(empty($data['translator'])){
            $data['status']='fail';
            $data['message']='not valid translator';
            return response()->json($data);
        }
        $limit = 5 ;
        $offset = ($page - 1)*$limit;
        $data['books'] = \App\Book::whereHas('translators',function($q) use ($id){
            $q->where('id',$id);
        })->where('active',1)->orderBy('created_at','DESC')
            ->skip($offset)->take($limit)->get();
        return response()->json($data);
    }


    public function search(Request $request,$page=1){
        $data['status']='success';
        $limit = 5 ;
        $offset = ($page - 1)*$limit;
        if($request->has('query')){
            $query = $request->get('query');
            $books = \App\Book::with(['writers','translators','tags','publication','category'])
                        ->where('title','like','%'.$query.'%')
                        ->orWhereHas('category',function($q) use ($query){
                            $q->where('title','like','%'.$query.'%');
                        })
                        ->orWhereHas('tags',function($q) use ($query){
                            $q->where('value','like','%'.$query.'%');
                        })
                        ->orWhereHas('writers',function($q) use ($query){
                            $q->where('name','like','%'.$query.'%');
                        })
                        ->orWhereHas('translators',function($q) use ($query){
                            $q->where('name','like','%'.$query.'%');
                        })
                        ->orWhereHas('publication',function($q) use ($query){
                            $q->where('title','like','%'.$query.'%');
                        })
                        ->where('active',1)->skip($offset)->take($limit)
                        ->orderBy('created_at','DESC')
                        ->get();

            $magazines = \App\Magazine::with(['tags','publication','category'])
                ->where('title','like','%'.$query.'%')
                ->orWhereHas('category',function($q) use ($query){
                    $q->where('title','like','%'.$query.'%');
                })
                ->orWhereHas('tags',function($q) use ($query){
                    $q->where('value','like','%'.$query.'%');
                })
                ->orWhereHas('publication',function($q) use ($query){
                    $q->where('title','like','%'.$query.'%');
                })
                ->where('active',1)->skip($offset)->take($limit)
                ->orderBy('created_at','DESC')
                ->get();
            $data['books'] = $books;
            $data['magazines'] = $magazines;
        }else{
            $data['status']='fail';
            $data['message']='method not allowed';
        }
        return response()->json($data);
    }


    public function category($category_id , $page = 1){
        $data['status']='success';
        $limit = 5 ;
        $offset = ($page - 1)*$limit;

        $books = \App\Book::with(['writers','translators','tags','publication','category'])
            ->where('category_id',$category_id)
            ->where('active',1)->skip($offset)->take($limit)
            ->orderBy('created_at','DESC')
            ->get();

        $magazines = \App\Magazine::with(['tags','publication','category'])
            ->where('category_id',$category_id)
            ->where('active',1)->skip($offset)->take($limit)
            ->orderBy('created_at','DESC')
            ->get();
        $data['books'] = $books;
        $data['magazines'] = $magazines;
        return response()->json($data);

    }

    public function tag($tag_id , $page = 1 ){
        $data['status']='success';
        $limit = 5 ;
        $offset = ($page - 1)*$limit;

        $books = \App\Book::with(['writers','translators','tags','publication','category'])
            ->whereHas('tags',function($query) use ($tag_id){
                $query->where('id',$tag_id);
            })
            ->where('active',1)->skip($offset)->take($limit)
            ->orderBy('created_at','DESC')
            ->get();

        $magazines = \App\Magazine::with(['tags','publication','category'])
            ->whereHas('tags',function($query) use ($tag_id){
                $query->where('id',$tag_id);
            })
            ->where('active',1)->skip($offset)->take($limit)
            ->orderBy('created_at','DESC')
            ->get();
        $data['books'] = $books;
        $data['magazines'] = $magazines;
        return response()->json($data);
    }
}
