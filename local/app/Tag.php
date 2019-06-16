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
        return $this->belongsToMany('App\Book','_tag_book')->orderBy('created_at','DESC')->take(5);
    }
    public function magazines_four(){
        return $this->belongsToMany('App\Magazine','_tag_magazine')->orderBy('created_at','DESC')->take(5);
    }

    public function books_ten(){
        return $this->belongsToMany('App\Book','_tag_book')->with(['translators','writers','publication','category','tags'])->orderBy('created_at','DESC')->take(11);
    }
    public function magazines_ten(){
        return $this->belongsToMany('App\Magazine','_tag_magazine')->with(['publication','category','tags'])->orderBy('created_at','DESC')->take(11);
    }
}
