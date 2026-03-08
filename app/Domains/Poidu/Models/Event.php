<?php

namespace App\Domains\Poidu\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use function Symfony\Component\Clock\now;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'date_time',
        'price_min',
        'price_max',
        'channel',
        'channel_id',
        'post_id',
        'link_to_post',
        'post_was_created',
        'approved',
        'views',
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
}
