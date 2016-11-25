<?php

namespace App\Http\Controllers\Api;

use App\Book;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;

class ApiController extends Controller
{
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
}
