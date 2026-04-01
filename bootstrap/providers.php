<?php

return [
    App\Providers\AppServiceProvider::class,

    /**
     * Domains
     */
    App\Domains\Poidu\App\src\PoiduAppServiceProvider::class,
    App\Domains\Poidu\Pipeline\src\PoiduPipelineServiceProvider::class,
];
