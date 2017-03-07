<?php

namespace App\Payments;

class PayIR extends Payment {

    public function send($order_id){
        $data = [
            'api'=>$this->setting->api,
            'amount'=>$this->amount,
            'redirect'=>$this->redirect,
            'factorNumber'=>$order_id,
        ];

        $handle = curl_init('https://pay.ir/payment/send');
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($handle);
        if (curl_errno($handle)) {
            dd(curl_error($handle));
        }
        curl_close($handle);
        return json_decode($result);
    }

    public function gateway($result){
        header("Location: https://pay.ir/payment/gateway/{$result->transId}");
        die();
    }

    public function gatelink($result){
        return "https://pay.ir/payment/gateway/{$result->transId}";
    }

    public function redirect($data){
        $result = (object) [];
        $result->status = 'done';
        $result->message = 'پرداخت با موفقیت انجام شد.';
        if($data->status == 0){
            $result->status  = 'fail';
            $result->message = $data->message;
        }
        return $result;
    }

    public function verify($result){
        $data = [
            'api'=>$this->setting->api,
            'transId'=>$result->transId,
        ];

        $handle = curl_init('https://pay.ir/payment/verify');
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        $request = curl_exec($handle);
        curl_close($handle);
        return json_decode($request);
    }

    public function hasError($result){
        $data = (object) [];
        $data->error = false;
        $data->message = 'فرایند کامل انجام شده است.';

        if($result->status == 0){
            $data->error = true;
            $data->message = $result->errorMessage;
        }

        return $data;
    }
}