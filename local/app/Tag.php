<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "tags";

    protected $fillable = [
        'value'
    ];

    public function books(){
        return $this->belongsToMany('App\Book','_tag_book');
    }
    public function magazines(){
        return $this->belongsToMany('App\Book','_tag_magazine');
    }

    public function books_four(){
        return $this->belongsToMany('App\Book','_tag_book')->orderBy('created_at','DESC')->take(4);
    }
    public function magazines_four(){
        return $this->belongsToMany('App\Magazine','_tag_magazine')->orderBy('created_at','DESC')->take(4);
    }
}
