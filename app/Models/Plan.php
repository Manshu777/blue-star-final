<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
     protected $fillable = [
        'name',
        'price',
        'storage_limit',
        'photo_upload_limit',
        'facial_recognition_enabled',
        'merchandise_enabled',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
