<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected $fillable = [
        'name',
        'email',
        'rating',
        'comment',
        'image',
        'title',
        'location',
        
    ];
}
