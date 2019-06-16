<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    function order(Request $request){
        $user = $request->user();
        $total_price = 0;
        $books_id = $request->books_id;
        $magazines_id = $request->magazines_id;

        $books = [];
        $magazines = [];
        if(!empty($books_id))
            $books = \App\Book::whereIn('id',$books_id)->where('active',1)->get();
        if(!empty($magazines_id))
            $magazines = \App\Magazine::whereIn('id',$magazines_id)->where('active',1)->get();

        $collect_books = collect($books);
        $total_price = $total_price + $collect_books->sum('price');

        $collect_magazine = collect($magazines);
        $total_price = $total_price + $collect_magazine->sum('price');

        $code = substr(str_shuffle("0123456789" . time()),0,8);
        while (\App\Order::where('code', $code)->get()->count() > 0) {
            $code = substr(str_shuffle("0123456789" . time()),0,8);
        }

        $order = new \App\Order();
        $order->user_id = $user->id;
        $order->code = $code;
        $order->price = $total_price;
        $order->save();

        $data_books = null;
        foreach($books as $item){
            $data_books[] = ['order_id'=>$order->id , 'book_id' => $item->id , 'price' => $item->price ];
        }
        if(!empty($data_books)){
            DB::table('_order_book')->insert(
                $data_books
            );
        }

        $data_magazines = null;
        foreach($magazines as $item){
            $data_magazines[] = ['order_id'=>$order->id , 'magazine_id' => $item->id , 'price' => $item->price ];
        }
        if(!empty($data_magazines)){
            DB::table('_order_magazine')->insert(
                $data_magazines
            );
        }

        $data['status'] = 'done';
        $data['order'] = \App\Order::with(['user','books','magazines'])->where('code',$order->code)->first();
        return response()->json($data);

    }

    function bill(Request $request,$code){
        $data['status'] = 'done';
        $order = \App\Order::with(['user','books','magazines'])->where('user_id',$request->user()->id)->where('code',code)->first();
        if(empty($order)){
            abort(404);
        }
        $data['order']= $order;
        if($order->pay == 1){
            $data['payment'] = \App\OrderPayment::with(['payment'])->where('order_id',$order->id)->where('pay',1)->first();
        }
        return response()->json($data);
    }

    function payments(Request $request){
        $data['status'] = 'done';
        $data['payments'] = \App\Payment::where('active',1)->get();
        return response()->json($data);
    }
}
