<?php

namespace App\Domains\Poidu\Pipeline\src;

use App\Domains\Poidu\Pipeline\Console\GeneratePreviewCommand;
use App\Domains\Poidu\Pipeline\Console\GetDetailsCommand;
use App\Domains\Poidu\Pipeline\Console\GetPromptCommand;
use App\Domains\Poidu\Pipeline\Console\ParsingTelegramCommand;
use App\Domains\Poidu\Pipeline\Console\PipelineEventMiningCommand;
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
            GetPromptCommand::class,
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
