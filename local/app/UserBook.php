<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBook extends Model
{
    protected $table = '_user_book';

    public function book(){
        return $this->belongsTo('App\Book');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
