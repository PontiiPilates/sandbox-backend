<?php

namespace App\Domains\Poidu\Pipeline\Console;

use App\Domains\Poidu\Pipeline\Traits\Timer;
use Illuminate\Console\Command;

class UpdateDetailsCommand extends Command
{
    use Timer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:update-details {pipelineId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет новыми деталями';

    /**
     * Execute the console command.
     */
    public function handle() {}
}
