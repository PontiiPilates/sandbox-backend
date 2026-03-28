<?php

namespace App\Domains\Poidu\Pipeline\Console;

use App\Domains\Poidu\Pipeline\Models\PipelineEventMining;
use Illuminate\Console\Command;

class GeneratePreviewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:generate-preview {pipelineId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерация preview для мероприятия';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dump('Генерирую изображения');

        $pipeline = PipelineEventMining::find($this->argument('pipelineId'));
        $pipeline->update(['preview' => now()]);
    }
}
