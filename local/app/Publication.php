<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $table = 'publications';

    protected $fillable = [
        'title',
    ];

    public function books(){
        return $this->hasMany('App\Book','publication_id');
    }

    public function magazines(){
        return $this->hasMany('App\Magazine','publication_id');
    }

    public function books_four(){
        return $this->hasMany('App\Book','publication_id')->orderBy('created_at','DESC')->take(4);
    }

    public function magazines_four(){
        return $this->hasMany('App\Magazine','publication_id')->orderBy('created_at','DESC')->take(4);
    }
}
