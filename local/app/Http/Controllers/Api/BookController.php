<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BookController extends Controller
{

    public function book($id){
        $data['status'] = 'done';
        $data['book'] = \App\Book::with(['writers','translators','category','publication','tags'])
            ->where('active',1)->where('id',$id)->first();
        if(empty($data['book'])){
            $data['status'] = 'fail';
            $data['message'] = 'not valid book';
        }
        $tag_ids = $data['book']->tags()->pluck('id');
        $data['related_books'] = \App\Book::whereHas('tags', function($q) use ($tag_ids) {
            $q->whereIn('id', $tag_ids);
        })
            ->where('id','!=',$id)
            ->orderBy('created_at')
            ->take(10)
            ->get();
        return response()->json($data);
    }

    public function books(Request $request,$page = 1,$orderBy = 'created_at' , $order = 'DESC'){
        $limit = 10 ;
        $offset = ($page - 1)*$limit;
        $data['status'] = 'done';
        $data['books'] = \App\Book::with(['writers','translators','category','publication','tags'])
            ->where('active',1)
            ->skip($offset)->take($limit);
        switch($orderBy){
            case "created_at":
                $data['books'] = $data['books']
                    ->orderBy('created_at',$order)
                    ->get();
                break;
            case "price":
                $data['books'] = $data['books']
                    ->orderBy('price',$order)
                    ->get();
                break;
            case "publish_date":
                $data['books'] = $data['books']
                    ->orderBy('publish_date',$order)
                    ->get();
                break;
            default:
                $data['books'] = $data['books']
                    ->get();
                break;
        }

        if($request->has('range')){
           $data['books'] = $this->range_job($request->get('range'),$data['books']->toArray());
        }

        return response()->json($data);
    }

    public function booksByCategory(Request $request,$category_id,$page = 1,$orderBy = 'created_at' , $order = 'DESC'){
        $limit = 10 ;
        $offset = ($page - 1)*$limit;
        $data['status'] = 'done';
        $data['books'] = \App\Book::with(['writers','translators','publication','tags'])
            ->whereHas('category',function($query) use ($category_id){
                $query->where('id',$category_id);
            })
            ->where('active',1)
            ->skip($offset)->take($limit);
        switch($orderBy){
            case "created_at":
                $data['books'] = $data['books']
                    ->orderBy('created_at',$order)
                    ->get();
                break;
            case "price":
                $data['books'] = $data['books']
                    ->orderBy('price',$order)
                    ->get();
                break;
            case "publish_date":
                $data['books'] = $data['books']
                    ->orderBy('publish_date',$order)
                    ->get();
                break;
            default:
                $data['books'] = $data['books']
                    ->get();
                break;
        }

        if($request->has('range')){
            $data['books'] = $this->range_job($request->get('range'),$data['books']->toArray());
        }

        return response()->json($data);
    }

    public function booksByTag(Request $request,$tag_id,$page = 1,$orderBy = 'created_at' , $order = 'DESC'){
        $limit = 10 ;
        $offset = ($page - 1)*$limit;
        $data['status'] = 'done';
        $data['books'] = \App\Book::with(['writers','translators','publication','tags','category'])
            ->whereHas('tags',function($query) use ($tag_id){
                $query->where('id',$tag_id);
            })
            ->where('active',1)
            ->skip($offset)->take($limit);
        switch($orderBy){
            case "created_at":
                $data['books'] = $data['books']
                    ->orderBy('created_at',$order)
                    ->get();
                break;
            case "price":
                $data['books'] = $data['books']
                    ->orderBy('price',$order)
                    ->get();
                break;
            case "publish_date":
                $data['books'] = $data['books']
                    ->orderBy('publish_date',$order)
                    ->get();
                break;
            default:
                $data['books'] = $data['books']
                    ->get();
                break;
        }
        if($request->has('range')){
            $data['books'] = $this->range_job($request->get('range'),$data['books']->toArray());
        }

        return response()->json($data);
    }

    public function addToBookshelfDemo(Request $request , $id){
        $data['status']='done';
        $data['message']='add';
        $user_id = $request->user()->id;
        $book_id = $id;
        $book = \App\Book::where('id',$book_id)->where('has_demo',1)->first();
        if(empty($book)){
            $data['status']='fail';
            $data['message']='can not find book';
            return response()->json($data);
        }
        if(\App\UserBook::where('user_id',$user_id)->where('book_id',$book_id)->where('demo',1)->count()>0){
            $data['status']='fail';
            $data['message']='exist in your shelf';
            return response()->json($data);
        }

        $user_book = new \App\UserBook();
        $user_book->user_id = $user_id;
        $user_book->book_id = $book_id;
        $user_book->demo = 1;
        $user_book->save();
        return response()->json($data);

    }

    public function addToBookshelfFree(Request $request , $id){
        $data['status']='done';
        $data['message']='add';
        $user_id = $request->user()->id;
        $book_id = $id;
        $book = \App\Book::where('id',$book_id)->where('price',0)->first();
        if(empty($book)){
            $data['status']='fail';
            $data['message']='can not find book';
            return response()->json($data);
        }
        if(\App\UserBook::where('user_id',$user_id)->where('book_id',$book_id)->where('demo',0)->count()>0){
            $data['status']='fail';
            $data['message']='exist in your shelf';
            return response()->json($data);
        }

        $user_book = new \App\UserBook();
        $user_book->user_id = $user_id;
        $user_book->book_id = $book_id;
        $user_book->save();
        return response()->json($data);

    }

    public function range_job($range,$items){
        list($start,$end) = explode(",",$range);
        $items = collect($items);
        $value = $items->where('price','>=',$start)->where('price','<=',$end);
        return $value->all();
    }
}
