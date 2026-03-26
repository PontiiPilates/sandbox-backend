<?php

namespace App\Domains\Poidu\Pipeline\Models;

use App\Domains\Poidu\Pipeline\Observers\PipelineEventMiningObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PipelineEventMiningObserver::class])]
class PipelineEventMining extends Model
{

    //
}
