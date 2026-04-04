<?php

namespace App\Domains\Poidu\Pipeline\src\Models;

use App\Domains\Poidu\Pipeline\src\Observers\PipelineEventMiningObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PipelineEventMiningObserver::class])]
class PipelineEventMining extends Model
{
    protected $table = 'pipeline_events_mining';

    protected $fillable = [
        '1_parsing',
        '2_classify',
        '3_classify_update',
        '4_beautify',
        '5_beautify_update',
        '6_imagenize',
        'failed',
    ];
}
