<?php

return [
    App\Providers\AppServiceProvider::class,

    /**
     * Domains
     */
    App\Domains\Poidu\App\src\PoiduAppServiceProvider::class,
    App\Domains\Poidu\Pipeline\src\PoiduPipelineServiceProvider::class,

    // todo: отключить после завершения работы над пайплайнами
    // App\Domains\Parsing\src\ParsingServiceProvider::class,
    // App\Domains\Illustrate\src\IllustrateServiceProvider::class,
];
