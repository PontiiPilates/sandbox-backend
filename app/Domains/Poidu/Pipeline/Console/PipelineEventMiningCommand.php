<?php

namespace App\Domains\Poidu\Pipeline\Console;

use App\Domains\Poidu\Pipeline\Models\PipelineEventMining;
use Illuminate\Console\Command;

class PipelineEventMiningCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:init-event-mining';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init pipeline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        PipelineEventMining::create([]);
    }
}
