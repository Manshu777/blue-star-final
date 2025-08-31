<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'monthly_price',
        'yearly_price',
        'billing_cycle',
        'storage_limit',
        'photo_upload_limit',
        'facial_recognition_enabled',
        'merchandise_enabled',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'description' => 'array', // Cast JSON to array for features
            'facial_recognition_enabled' => 'boolean',
            'merchandise_enabled' => 'boolean',
            'is_active' => 'boolean',
            'monthly_price' => 'float',
            'yearly_price' => 'float',
        ];
    }

    /**
     * Get the users associated with this plan.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}