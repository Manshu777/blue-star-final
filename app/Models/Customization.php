<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customization extends Model
{
     protected $fillable = ['user_id', 'merchandise_id', 'custom_image_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class);
    }
}
