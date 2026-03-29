<?php

namespace App\Domains\Poidu\Pipeline\Models;

use Illuminate\Database\Eloquent\Model;

class EventMining extends Model
{
    protected $fillable = [
        // колонки для сырых данных
        'peer',
        'peer_id',
        'post',
        'post_id',
        'date',
        'message',

        // колонки для обработанных данных
        'category_id',
        'additional_category_id',
        'title',
        'description',
        'date_time',
        'price_min',
        'price_max',
        'prompt',
        'preview',

        // общие колонки
        'source',
        'approved',
        'views',

        // метаданные
        'source_file',
    ];
}
