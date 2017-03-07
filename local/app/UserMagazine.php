<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMagazine extends Model
{
    protected $table = '_user_magazine';

    public function magazine(){
        return $this->belongsTo('App\Magazine');
    }

    public function user(){
        return $this->belongsTo('App\user');
    }
}
