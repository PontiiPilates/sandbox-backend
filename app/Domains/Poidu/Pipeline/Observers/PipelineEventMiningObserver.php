<?php

namespace App\Domains\Poidu\Pipeline\Observers;

use App\Domains\Poidu\Pipeline\Models\PipelineEventMining;
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
        // обновление поля $key запускает команду $value
        match (key($pipelineEventMining->getChanges())) {
            'parsing' => Artisan::call('pipeline:get-details', ['pipelineId' => $pipelineEventMining->id]),
            'details' => Artisan::call('pipeline:update-details', ['pipelineId' => $pipelineEventMining->id]),
            'update_details' => Artisan::call('pipeline:get-prompt', ['pipelineId' => $pipelineEventMining->id]),
            'prompt' => Artisan::call('pipeline:update_prompt', ['pipelineId' => $pipelineEventMining->id]),
            'update_prompt' => Artisan::call('pipeline:generate-preview', ['pipelineId' => $pipelineEventMining->id]),
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
