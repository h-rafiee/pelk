<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function books(){
        return $this->hasMany('App\Book','category_id');
    }

    public function magazines(){
        return $this->hasMany('App\Book','category_id');
    }

    public function books_four(){
        return $this->hasMany('App\Book','category_id')->orderBy('created_at','DESC')->take(4);
    }

    public function magazines_four(){
        return $this->hasMany('App\Book','category_id')->orderBy('created_at','DESC')->take(4);
    }
}
