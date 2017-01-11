<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $table = "_order_payment";
    protected $fillable = [
        'order_id','payment_id','pay','params'
    ];

    public function payment(){
        return $this->belongsTo('App\Payment','payment_id');
    }
}
