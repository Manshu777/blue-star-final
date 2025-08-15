<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Businesses extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'category_id',
        'description',
        'website',
        'phone',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'category_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
