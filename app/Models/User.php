<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'plan_id',
        'reference_selfie_path',
        'otp',
        'temp_selfie_path',

        'status',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


     public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

     public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * Accessor for total storage used by the user's photos (in MB, assuming file_size is in MB).
     */
    public function getTotalStorageUsedAttribute()
    {
        return $this->photos()->sum('file_size');
    }

    /**
     * Accessor for unique locations used in the user's photos.
     */
    public function getLocationsUsedAttribute()
    {
        return $this->photos()->distinct('location')->pluck('location')->filter()->join(', ');
    }

}
