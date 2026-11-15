<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribtion extends Model
{
    //
    protected $fillable = [
        'user_id',
        'stripe_session_id',
        'currency',
        'status',
        'amount',
        'current_period_start',
        'current_period_end',
    ];
}
