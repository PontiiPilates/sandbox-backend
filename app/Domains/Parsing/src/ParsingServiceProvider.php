<?php

namespace App\Domains\Parsing\src;

use App\Domains\Parsing\Console\ExtractionPreparationCommand;
use App\Domains\Parsing\Console\TgEventsExtractCommand;
use App\Domains\Parsing\Console\UpdateEventsCommand as ConsoleUpdateEventsCommand;
use App\Domains\Poidu\Console\Commands\UpdateEventsCommand;
use Illuminate\Support\ServiceProvider;

class ParsingServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        // parent::register();

        $this->commands([
            ExtractionPreparationCommand::class,
            TgEventsExtractCommand::class,
            ConsoleUpdateEventsCommand::class,
        ]);
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(realpath(__DIR__ . '/../config/services.php'), 'services');
        $this->loadRoutesFrom(realpath(__DIR__ . '/../routes/api.php'));
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));
        $this->loadTranslationsFrom(realpath(__DIR__ . '/../lang'), 'lang');
    }
}
