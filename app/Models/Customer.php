<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Customer extends Model
{
    
     protected $fillable = [
        'user_id',
        'billing_address',
        'phone',
        'preferred_payment_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
