<?php

namespace App\Domains\Poidu\Pipeline\Console;

use App\Domains\Poidu\Pipeline\Models\PipelineEventMining;
use Illuminate\Console\Command;

class GetDetailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:get-details {pipelineId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Возвращает детали мероприятия';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dump('Возвращаю детали мероприятия');

        $pipeline = PipelineEventMining::find($this->argument('pipelineId'));
        $pipeline->update(['details' => now()]);
    }
}
