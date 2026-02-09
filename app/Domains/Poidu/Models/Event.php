<?php

namespace App\Domains\Poidu\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'date_start',
        'time_start',
        'price_min',
        'price_max',
        'channel',
        'channel_id',
        'post_id',
        'link_to_post',
        'post_was_created',
    ];
}
