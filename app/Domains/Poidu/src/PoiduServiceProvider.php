<?php

namespace App\Domains\Poidu\src;

use App\Domains\Poidu\Console\AddFakeViewCommand;
use App\Domains\Poidu\Console\Commands\UpdateEventsCommand;
use Illuminate\Support\ServiceProvider;

class PoiduServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        // parent::register();

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
        $this->loadTranslationsFrom(realpath(__DIR__ . '/../lang'), 'lang');
    }
}
