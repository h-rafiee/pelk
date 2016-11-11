<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "tags";

    public function books(){
        return $this->belongsToMany('App\Book','_tag_book');
    }
}
