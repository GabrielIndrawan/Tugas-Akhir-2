<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'username',
        'premium',
        'current_quota',
    ];

    protected $casts = [
        'premium' => 'boolean',
        'current_quota' => 'integer',
    ];
}
