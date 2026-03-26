<?php

namespace App\Domains\Poidu\Pipeline\Observers;

use App\Domains\Poidu\Pipeline\Models\PipelineEventMining;

class PipelineEventMiningObserver
{
    /**
     * Handle the PipelineEventMining "created" event.
     */
    public function created(PipelineEventMining $pipelineEventMining): void
    {
        dd('Create');
    }

    /**
     * Handle the PipelineEventMining "updated" event.
     */
    public function updated(PipelineEventMining $pipelineEventMining): void
    {
        //
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
