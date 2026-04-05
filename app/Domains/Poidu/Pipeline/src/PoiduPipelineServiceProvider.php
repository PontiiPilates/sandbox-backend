<?php

namespace App\Domains\Poidu\Pipeline\src;

use App\Domains\Poidu\Pipeline\src\Console\BeautifyCommand;
use App\Domains\Poidu\Pipeline\src\Console\BeautifyUpdateCommand;
use App\Domains\Poidu\Pipeline\src\Console\ClassifyCommand;
use App\Domains\Poidu\Pipeline\src\Console\ClassifyUpdateCommand;
use App\Domains\Poidu\Pipeline\src\Console\ImagenizeCommand;
use App\Domains\Poidu\Pipeline\src\Console\ParsingTelegramCommand;
use App\Domains\Poidu\Pipeline\src\Console\PipelineEventMiningCommand;
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
            ClassifyCommand::class,
            ClassifyUpdateCommand::class,
            BeautifyCommand::class,
            BeautifyUpdateCommand::class,
            ImagenizeCommand::class,
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
