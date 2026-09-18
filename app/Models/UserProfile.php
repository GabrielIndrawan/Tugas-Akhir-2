<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'username',
        'premium',
    ];

    protected $casts = [
        'premium' => 'boolean',
    ];
}
