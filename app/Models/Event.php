<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'details',
        'max_concurrent_users',
        'queue_timeout_seconds',
    ];

    protected $casts = [
        'details' => 'array',
    ];
}
