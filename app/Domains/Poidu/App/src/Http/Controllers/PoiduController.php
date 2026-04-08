<?php

namespace App\Domains\Poidu\App\src\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoiduController extends Controller
{
    public function ping()
    {
        return config('services.poidu.ping');
    }

    public function seo()
    {
        return response()->json([
            'general' => [
                'title' => 'Куда сходить в Красноярске',
                'h1' => 'Куда сходить в Красноярске',
                'description' => ''
            ],
            'alternative' => [
                'title' => 'Туризм в Красноярске',
                'h1' => 'Туризм в Красноярске',
                'description' => ''
            ],
            'excursions' => [
                'title' => 'Экскурсии в Красноярске',
                'h1' => 'Экскурсии в Красноярске',
                'description' => ''
            ],
            'hiking' => [
                'title' => 'Походы в красноярске',
                'h1' => 'Походы в красноярске',
                'description' => ''
            ],
            'tours' => [
                'title' => 'Туристические туры в Красноярске',
                'h1' => 'Туристические туры в Красноярске',
                'description' => ''
            ],
            'mountains' => [
                'title' => 'Восхождения на горы Красноярского края',
                'h1' => 'Восхождения на горы Красноярского края',
                'description' => ''
            ],
            'speleo' => [
                'title' => 'Спуски в пещеры Красноярского края',
                'h1' => 'Спуски в пещеры Красноярского края',
                'description' => ''
            ],
            'water' => [
                'title' => 'Сплавы в Красноярске',
                'h1' => 'Сплавы в Красноярске',
                'description' => ''
            ],
        ]);
    }
}
