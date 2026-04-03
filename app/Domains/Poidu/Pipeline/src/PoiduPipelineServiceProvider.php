<?php

namespace App\Domains\Poidu\Pipeline\src;

use App\Domains\Poidu\Pipeline\src\Console\GeneratePreviewCommand;
use App\Domains\Poidu\Pipeline\src\Console\GetDetailsCommand;
use App\Domains\Poidu\Pipeline\src\Console\GetPromptCommand;
use App\Domains\Poidu\Pipeline\src\Console\ParsingTelegramCommand;
use App\Domains\Poidu\Pipeline\src\Console\PipelineEventMiningCommand;
use App\Domains\Poidu\Pipeline\src\Console\UpdateDetailsCommand;
use App\Domains\Poidu\Pipeline\src\Console\UpdatePromptCommand;
use Illuminate\Support\ServiceProvider;

class PoiduPipelineServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            PipelineEventMiningCommand::class,
            ParsingTelegramCommand::class,
            GetDetailsCommand::class,
            UpdateDetailsCommand::class,
            GetPromptCommand::class,
            UpdatePromptCommand::class,
            GeneratePreviewCommand::class,
        ]);
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(realpath(__DIR__ . '/../config/services.php'), 'services');
        $this->loadRoutesFrom(realpath(__DIR__ . '/../routes/console.php'));
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));
    }
}
