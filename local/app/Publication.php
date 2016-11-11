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
}
