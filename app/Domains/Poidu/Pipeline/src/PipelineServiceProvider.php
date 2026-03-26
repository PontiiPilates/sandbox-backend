<?php

namespace App\Domains\Poidu\Pipeline\src;

use App\Domains\Illustrate\Console\CreatePreview;
use Illuminate\Support\ServiceProvider;

class PipelineServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            // CreatePreview::class,
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
