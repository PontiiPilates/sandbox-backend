<?php

namespace App\Domains\Poidu\Pipeline\src\Observers;

use App\Domains\Poidu\Pipeline\src\Models\PipelineEventMining;
use Illuminate\Support\Facades\Artisan;

class PipelineEventMiningObserver
{
    /**
     * Handle the PipelineEventMining "created" event.
     */
    public function created(PipelineEventMining $pipelineEventMining): void
    {
        Artisan::call('pipeline:parsing-telegram', ['pipelineId' => $pipelineEventMining->id]);
    }

    /**
     * Handle the PipelineEventMining "updated" event.
     */
    public function updated(PipelineEventMining $pipelineEventMining): void
    {
        match (key($pipelineEventMining->getChanges())) {
            '1_parsing' => Artisan::call('pipeline:classify', ['pipelineId' => $pipelineEventMining->id]),
            '2_classify' => Artisan::call('pipeline:classify-update', ['pipelineId' => $pipelineEventMining->id]),
            '3_classify_update' => Artisan::call('pipeline:beautify', ['pipelineId' => $pipelineEventMining->id]),
            '4_beautify' => Artisan::call('pipeline:beautify-update', ['pipelineId' => $pipelineEventMining->id]),
            // '5_beautify_update' => Artisan::call('pipeline:imagenize', ['pipelineId' => $pipelineEventMining->id]),
            default => null,
        };
    }

    /**
     * Handle the PipelineEventMining "deleted" event.
     */
    public function deleted(PipelineEventMining $pipelineEventMining): void
    {
        //
    }

    /**
     * Handle the PipelineEventMining "restored" event.
     */
    public function restored(PipelineEventMining $pipelineEventMining): void
    {
        //
    }

    /**
     * Handle the PipelineEventMining "force deleted" event.
     */
    public function forceDeleted(PipelineEventMining $pipelineEventMining): void
    {
        //
    }
}
