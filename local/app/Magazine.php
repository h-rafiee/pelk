<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Magazine extends Model
{
    protected $table = 'magazines';

    public function category(){
        return $this->belongsTo('App\Category','category_id');
    }

    public function publication(){
        return $this->belongsTo('App\Publication','publication_id');
    }

    public function tags(){
        return $this->belongsToMany('App\Tag','_tag_magazine','magazine_id');
    }
}
