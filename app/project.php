<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class project extends Model
{
   protected $fillable = [
        'title', 'url', 'description',
    ];
}
