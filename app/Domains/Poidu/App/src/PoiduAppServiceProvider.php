<?php

namespace App\Domains\Poidu\App\src;

use App\Domains\Poidu\App\src\Console\AddFakeViewCommand;
use App\Domains\Poidu\App\src\Console\RefreshSitemapCommand;
use Illuminate\Support\ServiceProvider;

class PoiduAppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            AddFakeViewCommand::class,
            RefreshSitemapCommand::class,
        ]);
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(realpath(__DIR__ . '/../config/services.php'), 'services');
        $this->loadRoutesFrom(realpath(__DIR__ . '/../routes/api.php'));
        $this->loadRoutesFrom(realpath(__DIR__ . '/../routes/console.php'));
        $this->loadRoutesFrom(realpath(__DIR__ . '/../routes/web.php'));
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));
        $this->loadViewsFrom(realpath(__DIR__ . '/../resources/views'), 'poidu');

        $this->publishes([
            realpath(__DIR__ . '/../resources/volt/assets') => public_path('volt/assets'),
            realpath(__DIR__ . '/../resources/volt/css') => public_path('volt/css'),
            realpath(__DIR__ . '/../resources/volt/vendor') => public_path('volt/vendor'),
        ], 'volt');
    }
}
