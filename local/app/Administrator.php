<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    protected $table = "administrators";

    protected $fillable = [
        'name','username','mobile', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
}
