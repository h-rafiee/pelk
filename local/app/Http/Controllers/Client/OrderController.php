<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Crypt;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    function postOrder(Request $request)
    {
        $type = Crypt::decrypt($request->_item_type);
        $slug = Crypt::decrypt($request->_item);
        switch ($type) {
            case "book" :
                $model = \App\Book::where('slug', $slug)->first();
                $code = substr(str_shuffle("0123456789" . time()),0,8);
                while (\App\Order::where('code', $code)->get()->count() > 0) {
                    $code = substr(str_shuffle("0123456789" . time()),0,8);
                }
                $order = new \App\Order();
                $order->code = $code;
                $order->price = $model->price;
                $order->user_id = Auth::user()->id;
                $order->save();
                DB::table('_order_book')->insert(
                    ['order_id' => $order->id, 'book_id' => $model->id, 'price' => $model->price]
                );
                return redirect("bill/{$order->code}");
                break;
            case "magazine" :
                $model = \App\Magazine::where('slug', $slug)->first();
                $code = substr(str_shuffle("0123456789" . time()),0,8);
                while (\App\Order::where('code', $code)->get()->count() > 0) {
                    $code = substr(str_shuffle("0123456789" . time()),0,8);
                }
                $order = new \App\Order();
                $order->code = $code;
                $order->price = $model->price;
                $order->user_id = Auth::user()->id;
                $order->save();
                DB::table('_order_magazine')->insert(
                    ['order_id' => $order->id, 'magazine_id' => $model->id, 'price' => $model->price]
                );
                return redirect("bill/{$order->code}");
                break;
            default :
                return redirect('/')->withErrors(['error'=>'request failed']);
                break;
        }
    }

    function getBill($code){
        $order = \App\Order::with(['user','books','magazines'])->where('user_id',Auth::user()->id)->where('code',$code)->first();
        if(empty($order)){
            die("ERROR");
        }
        if($order->pay == 0){
            $payments  = \App\Payment::where('active',1)->get();

        }else{
            $payments  = \App\OrderPayment::with(['payment'])->where('order_id',$order->id)->where('pay',1)->first();
        }

        $data = [
            'model'=>$order,
            'payments'=>$payments
        ];
        return view('bill',$data);
    }

    function postBill(Request $request,$code){
        $slug = $request->payment;
        $payment = \App\Payment::where('slug',$slug)->first();
        if(empty($payment)){
            die("ERROR Payment");
        }
        $order = \App\Order::with(['user','books','magazines'])->where('user_id',Auth::user()->id)->where('code',$code)->where('pay',0)->first();
        if(empty($order)){
            die("ERROR Order");
        }
        $class = "\\App\\Payments\\".$payment->class_name;
        $pay = new $class();
        $setting = json_decode($payment->params);
        $pay->setSetting($setting);
        $pay->setAmount($order->price);
        $pay->setRedirect(url("payment/retrieve/{$payment->slug}/{$order->code}"));
        $result = $pay->send($order->id);
        dd($result);
        $orderPay = \App\OrderPayment::updateOrCreate(
            ['order_id' => $order->id, 'payment_id' => $payment->id],
            ['params' => json_encode($result)]
        );
        $error = $pay->hasError($result);
        if($error->error==true){
            die($error->message);
        }
        return $pay->gateway($result);

    }

    function postRetrieve(Request $request,$payment,$code){

        $payment = \App\Payment::where('slug',$payment)->first();
        if(empty($payment)){
            die("ERROR Payment");
        }
        $order = \App\Order::with(['user','books','magazines'])->where('user_id',Auth::user()->id)->where('code',$code)->first();
        if(empty($order)){
            die("ERROR Order");
        }
        $class = "\\App\\Payments\\".$payment->class_name;
        $pay = new $class();
        $setting = json_decode($payment->params);
        $pay->setSetting($setting);
        $check=$pay->redirect((object) $request->all());
        if($check->status == 'fail'){
            $orderPay = \App\OrderPayment::updateOrCreate(
                ['order_id' => $order->id, 'payment_id' => $payment->id],
                ['params' => json_encode($request->all())]
            );
            die("ERROR die");
        }
        $result = $pay->verify((object) $request->all());
        $orderPay = \App\OrderPayment::updateOrCreate(
            ['order_id' => $order->id, 'payment_id' => $payment->id],
            ['params' => json_encode($result)]
        );
        $error = $pay->hasError($result);
        if($error->error==true){
            die($error->message);
        }
        $order->pay = 1;
        $order->save();
        $orderPay = \App\OrderPayment::updateOrCreate(
            ['order_id' => $order->id, 'payment_id' => $payment->id],
            ['pay' => 1]
        );
        foreach($order->books as $item){
            $user_book = new \App\UserBook();
            $user_book->user_id = $order->user_id;
            $user_book->book_id = $item->id;
            $user_book->save();
        }
        foreach($order->magazines as $item){
            $user_magazine = new \App\UserMagazine();
            $user_magazine->user_id = $order->user_id;
            $user_magazine->magazine_id = $item->id;
            $user_magazine->save();
        }
        return redirect('bill/'.$order->code)->with(['status'=>'done','message'=>$check->message]);
    }
    // Mobiles
    function postRetrieveMob(Request $request,$payment,$code){
        $payment = \App\Payment::where('slug',$payment)->first();
        if(empty($payment)){
            die("ERROR Payment");
        }
        $order = \App\Order::with(['user','books','magazines'])->where('code',$code)->first();
        if(empty($order)){
            die("ERROR Order");
        }

        $class = "\\App\\Payments\\".$payment->class_name;
        $pay = new $class();
        $setting = json_decode($payment->params);
        $pay->setSetting($setting);
        $check=$pay->redirect((object) $request->all());
        if($check->status == 'fail'){
            $orderPay = \App\OrderPayment::updateOrCreate(
                ['order_id' => $order->id, 'payment_id' => $payment->id],
                ['params' => json_encode($request->all())]
            );
            die("ERROR die");
        }
        $result = $pay->verify((object) $request->all());
        $orderPay = \App\OrderPayment::updateOrCreate(
            ['order_id' => $order->id, 'payment_id' => $payment->id],
            ['params' => json_encode($result)]
        );
        $error = $pay->hasError($result);
        if($error->error==true){
            die($error->message);
        }
        $order->pay = 1;
        $order->save();
        $orderPay = \App\OrderPayment::updateOrCreate(
            ['order_id' => $order->id, 'payment_id' => $payment->id],
            ['pay' => 1]
        );

        foreach($order->books as $item){
            $user_book = new \App\UserBook();
            $user_book->user_id = $order->user_id;
            $user_book->book_id = $item->id;
            $user_book->save();
        }
        foreach($order->magazines as $item){
            $user_magazine = new \App\UserMagazine();
            $user_magazine->user_id = $order->user_id;
            $user_magazine->magazine_id = $item->id;
            $user_magazine->save();
        }
        return redirect('bill/'.$order->code.'/mob')->with(['status'=>'done','message'=>$check->message]);
    }

    function getBillMob($code){
        $order = \App\Order::with(['user','books','magazines'])->where('code',$code)->first();
        if(empty($order)){
            die("ERROR");
        }
        if($order->pay == 0){
            $payments  = \App\Payment::where('active',1)->get();

        }else{
            $payments  = \App\OrderPayment::with(['payment'])->where('order_id',$order->id)->where('pay',1)->first();
        }

        $data = [
            'model'=>$order,
            'payments'=>$payments
        ];
        return view('bill_mob',$data);
    }

    function gateway($payment_slug,$order_code){
        $data['status']='done';
        $payment = \App\Payment::where('slug',$payment_slug)->first();
        if(empty($payment)){
            die("ERROR Payment");
        }
        $order = \App\Order::with(['user','books','magazines'])->where('code',$order_code)->where('pay',0)->first();
        if(empty($order)){
            die("ERROR Order");
        }
        $class = "\\App\\Payments\\".$payment->class_name;
        $pay = new $class();
        $setting = json_decode($payment->params);
        $pay->setSetting($setting);
        $pay->setAmount($order->price);
        $pay->setRedirect(url("/payment/retrieve/{$payment->slug}/{$order->code}/mob"));
        $result = $pay->send($order->id);
        $orderPay = \App\OrderPayment::updateOrCreate(
            ['order_id' => $order->id, 'payment_id' => $payment->id],
            ['params' => json_encode($result)]
        );
        $error = $pay->hasError($result);
        if($error->error==true){
            die($error->message);
        }
        return $pay->gateway($result);
    }

}