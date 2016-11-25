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

    public function range_job($range,$items){
        list($start,$end) = explode(",",$range);
        $items = collect($items);
        $value = $items->where('price','>=',$start)->where('price','<=',$end);
        return $value->all();
    }
}
