<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   protected $fillable = [
     'user_id',
        'title',
        'description',
        'image_path',
        'watermarked_path',
        'price',
        'is_featured',
        'license_type',
        'tags',
        'metadata',
        'tour_provider',
        'location',
        'event',
        'date',
        'file_size',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_featured' => 'boolean',
        'date' => 'datetime',
        'file_size' => 'float',
    ];

    /**
     * Get the user that owns the photo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders for the photo (morph many).
     */
    public function orders()
    {
        return $this->morphMany(Order::class, 'item');
    }
}