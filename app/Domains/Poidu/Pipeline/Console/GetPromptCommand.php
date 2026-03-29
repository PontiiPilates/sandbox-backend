<?php

namespace App\Domains\Poidu\Pipeline\Console;

use App\Domains\Poidu\Pipeline\Models\PipelineEventMining;
use Illuminate\Console\Command;

class GetPromptCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:get-prompt {pipelineId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Возвращает промпты для генерации preview';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dump('Создаю промпты');
        dd("Управление передано успешно");

        $pipeline = PipelineEventMining::find($this->argument('pipelineId'));
        $pipeline->update(['prompt' => now()]);
    }
}
