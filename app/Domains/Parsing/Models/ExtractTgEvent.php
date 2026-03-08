<?php

namespace App\Domains\Parsing\Models;

use Illuminate\Database\Eloquent\Model;

class ExtractTgEvent extends Model
{
    protected $fillable = [
        'peer',
        'peer_id',
        'post',
        'post_id',
        'date',
        'message',
        'source',
        'ulid',
    ];
}
