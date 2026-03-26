<?php

namespace App\Domains\Poidu\App\src;

use App\Domains\Poidu\App\Console\AddFakeViewCommand;
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
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));
    }
}
