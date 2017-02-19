<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMagazine extends Model
{
    protected $table = '_user_magazine';

    protected $hidden = [
        'original_key'
    ];

    public function magazine(){
        return $this->belongsTo('App\Magazine');
    }

    public function user(){
        return $this->belongsTo('App\user');
    }
}
