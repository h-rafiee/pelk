<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    public function category(){
        return $this->belongsTo('App\Category','category_id');
    }

    public function publication(){
        return $this->belongsTo('App\Publication','publication_id');
    }

    public function tags(){
        return $this->belongsToMany('App\Tag','_tag_book','book_id');
    }

    public function writers(){
        return $this->belongsToMany('App\Author','_writer_book','book_id');
    }

    public function translators(){
        return $this->belongsToMany('App\Author','_translator_book','book_id');
    }
}
