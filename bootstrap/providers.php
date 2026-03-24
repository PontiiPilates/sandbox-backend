<?php

return [
    App\Providers\AppServiceProvider::class,

    /**
     * Domains
     */
    App\Domains\Poidu\src\PoiduServiceProvider::class,
    App\Domains\Parsing\src\ParsingServiceProvider::class,
    App\Domains\Illustrate\src\IllustrateServiceProvider::class,
];
