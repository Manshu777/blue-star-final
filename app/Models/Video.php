<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'video_path',
        'price',
        'is_featured',
        'license_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->morphMany(Order::class, 'item');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable', 'video_tag');
    }
}
