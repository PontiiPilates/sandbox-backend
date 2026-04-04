<?php

namespace App\Domains\Poidu\Pipeline\src\Models;

use App\Domains\Poidu\App\src\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventMining extends Model
{
    protected $table = 'events_mining';

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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Возвращает состояние события. Событие устарело, если его datetime находится в прошлом.
     * @return bool
     */
    public function getIsActiveAttribute(): bool
    {
        $eventDatetime = Carbon::parse($this->date_start . ' ' . $this->time_start, 'Asia/Krasnoyarsk');

        if (now('Asia/Krasnoyarsk') > $eventDatetime) {
            return false;
        }
        return true;
    }

    public function getDateStartAttribute()
    {
        $dateStart = Carbon::parse($this->date_time);
        return $dateStart->isoFormat('YYYY-MM-DD');
    }

    public function getTimeStartAttribute()
    {
        $timeStart = Carbon::parse($this->date_time);
        return $timeStart->isoFormat('HH:mm');
    }
}
