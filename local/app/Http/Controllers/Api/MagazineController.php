<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MagazineController extends Controller
{

    public function magazine($id){
        $data['status'] = 'done';
        $data['magazine'] = \App\Magazine::with(['category','publication','tags'])
            ->where('active',1)->where('id',$id)->first();
        if(empty($data['magazine'])){
            $data['status'] = 'fail';
            $data['message'] = 'not valid magazine';
        }
        $tag_ids = $data['magazine']->tags()->pluck('id');
        $data['related_magazines'] = \App\Magazine::whereHas('tags', function($q) use ($tag_ids) {
                $q->whereIn('id', $tag_ids);
            })
            ->where('id','!=',$id)
            ->orderBy('created_at')
            ->take(10)
            ->get();
        return response()->json($data);
    }

    public function magazines(Request $request,$page = 1,$orderBy = 'created_at' , $order = 'DESC'){
        $limit = 10 ;
        $offset = ($page - 1)*$limit;
        $data['status'] = 'done';
        $data['magazines'] = \App\Magazine::with(['category','publication','tags'])
            ->where('active',1)
            ->skip($offset)->take($limit);
        switch($orderBy){
            case "created_at":
                $data['magazines'] = $data['magazines']
                    ->orderBy('created_at',$order)
                    ->get();
                break;
            case "price":
                $data['magazines'] = $data['magazines']
                    ->orderBy('price',$order)
                    ->get();
                break;
            case "publish_date":
                $data['magazines'] = $data['magazines']
                    ->orderBy('publish_date',$order)
                    ->get();
                break;
            default:
                $data['magazines'] = $data['magazines']
                    ->get();
                break;
        }

        if($request->has('range')){
            $data['magazines'] = $this->range_job($request->get('range'),$data['magazines']->toArray());
        }

        return response()->json($data);
    }

    public function magazinesByCategory(Request $request,$category_id,$page = 1,$orderBy = 'created_at' , $order = 'DESC'){
        $limit = 10 ;
        $offset = ($page - 1)*$limit;
        $data['status'] = 'done';
        $data['magazines'] = \App\Magazine::with(['publication','tags'])
            ->whereHas('category',function($query) use ($category_id){
                $query->where('id',$category_id);
            })
            ->where('active',1)
            ->skip($offset)->take($limit);
        switch($orderBy){
            case "created_at":
                $data['magazines'] = $data['magazines']
                    ->orderBy('created_at',$order)
                    ->get();
                break;
            case "price":
                $data['magazines'] = $data['magazines']
                    ->orderBy('price',$order)
                    ->get();
                break;
            case "publish_date":
                $data['magazines'] = $data['magazines']
                    ->orderBy('publish_date',$order)
                    ->get();
                break;
            default:
                $data['magazines'] = $data['magazines']
                    ->get();
                break;
        }

        if($request->has('range')){
            $data['magazines'] = $this->range_job($request->get('range'),$data['magazines']->toArray());
        }

        return response()->json($data);
    }

    public function magazinesByTag(Request $request,$tag_id,$page = 1,$orderBy = 'created_at' , $order = 'DESC'){
        $limit = 10 ;
        $offset = ($page - 1)*$limit;
        $data['status'] = 'done';
        $data['magazines'] = \App\Magazine::with(['publication','category'])
            ->whereHas('tags',function($query) use ($tag_id){
                $query->where('id',$tag_id);
            })
            ->where('active',1)
            ->skip($offset)->take($limit);
        switch($orderBy){
            case "created_at":
                $data['magazines'] = $data['magazines']
                    ->orderBy('created_at',$order)
                    ->get();
                break;
            case "price":
                $data['magazines'] = $data['magazines']
                    ->orderBy('price',$order)
                    ->get();
                break;
            case "publish_date":
                $data['magazines'] = $data['magazines']
                    ->orderBy('publish_date',$order)
                    ->get();
                break;
            default:
                $data['magazines'] = $data['magazines']
                    ->get();
                break;
        }

        if($request->has('range')){
            $data['magazines'] = $this->range_job($request->get('range'),$data['magazines']->toArray());
        }

        return response()->json($data);
    }

    public function addToBookshelfDemo(Request $request , $id){
        $data['status']='done';
        $data['message']='add';
        $user_id = $request->user()->id;
        $magazine_id = $id;
        $magazine = \App\Magazine::where('id',$magazine_id)->where('has_demo',1)->first();
        if(empty($magazine)){
            $data['status']='fail';
            $data['message']='can not find magazine';
            return response()->json($data);
        }
        if(\App\UserMagazine::where('user_id',$user_id)->where('magazine_id',$magazine_id)->where('demo',1)->count()>0){
            $data['status']='fail';
            $data['message']='exist in your shelf';
            return response()->json($data);
        }

        $user_magazine = new \App\UserMagazine();
        $user_magazine->user_id = $user_id;
        $user_magazine->magazine_id = $magazine_id;
        $user_magazine->demo = 1;
        $user_magazine->save();
        return response()->json($data);

    }

    public function addToBookshelfFree(Request $request , $id){
        $data['status']='done';
        $data['message']='add';
        $user_id = $request->user()->id;
        $magazine_id = $id;
        $magazine = \App\Magazine::where('id',$magazine_id)->where('price',0)->first();
        if(empty($magazine)){
            $data['status']='fail';
            $data['message']='can not find magazine';
            return response()->json($data);
        }
        if(\App\UserMagazine::where('user_id',$user_id)->where('magazine_id',$magazine_id)->where('demo',0)->count()>0){
            $data['status']='fail';
            $data['message']='exist in your shelf';
            return response()->json($data);
        }

        $user_magazine = new \App\UserMagazine();
        $user_magazine->user_id = $user_id;
        $user_magazine->magazine_id = $magazine_id;
        $user_magazine->save();
        return response()->json($data);

    }

    public function range_job($range,$items){
        list($start,$end) = explode(",",$range);
        $items = collect($items);
        $value = $items->where('price','>=',$start)->where('price','<=',$end);
        return $value->all();
    }

    public function read(Request $request,$id,$demo = 'F'){
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
        $item->page = $request->get('page');
        $item->save();
        $data['status'] = 'done';
        $data['message'] = 'Magazine is page '.$item->page;
        return response()->json($data);
    }
}
