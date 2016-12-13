<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = "authors";

    protected $fillable = [
        'name','type'
    ];

    public function wbooks(){
        return $this->belongsToMany('App\Book','_writer_book','author_id');
    }

    public function tbooks(){
        return $this->belongsToMany('App\Book','_translator_book','author_id');
    }

    public function wbooks_four(){
        return $this->belongsToMany('App\Book','_writer_book','author_id')->orderBy('created_at','DESC')->take(4);
    }

    public function tbooks_four(){
        return $this->belongsToMany('App\Book','_translator_book','author_id')->orderBy('created_at','DESC')->take(4);
    }
}
