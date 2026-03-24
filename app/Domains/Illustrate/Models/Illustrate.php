<?php

namespace App\Domains\Illustrate\Models;

use Illuminate\Database\Eloquent\Model;

class Illustrate extends Model
{
    protected $fillable = [
        'event_id',
        'prompt',
        'name',
        'channel_id',
        'post_id'
    ];
}
