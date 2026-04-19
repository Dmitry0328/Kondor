<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineVisitor extends Model
{
    protected $fillable = [
        'fingerprint',
        'session_id',
        'user_id',
        'ip_address',
        'user_agent',
        'last_seen_at',
        'last_path',
        'context',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];
}
