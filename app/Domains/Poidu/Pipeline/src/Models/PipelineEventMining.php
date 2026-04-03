<?php

namespace App\Domains\Poidu\Pipeline\src\Models;

use App\Domains\Poidu\Pipeline\src\Observers\PipelineEventMiningObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PipelineEventMiningObserver::class])]
class PipelineEventMining extends Model
{
    protected $fillable = [
        'parsing',
        'details',
        'update_details',
        'prompt',
        'preview',
        'failed',
    ];
}
