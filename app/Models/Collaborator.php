<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    protected $fillable = ['user_id', 'album_name', 'email'];

    /**
     * The user who owns the album.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}