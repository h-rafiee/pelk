<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function books(){
        return $this->belongsToMany('App\Book','_order_book');
    }

    public function magazines(){
        return $this->belongsToMany('App\Magazine','_order_magazine');
    }
}
