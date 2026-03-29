<?php

namespace App\Domains\Poidu\Pipeline\Console;

use App\Domains\Poidu\Pipeline\Traits\Timer;
use Illuminate\Console\Command;

class UpdatePromptCommand extends Command
{
    use Timer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:update-prompt {pipelineId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет промптом для генерации изображенией и некоторыми другими данными';

    /**
     * Execute the console command.
     */
    public function handle() {}
}
