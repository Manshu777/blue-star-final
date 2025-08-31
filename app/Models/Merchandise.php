<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Merchandise extends Model
{


    protected $fillable = [
        'name',
        'description',
        'image_path',
        'price',
        'stock',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

}
