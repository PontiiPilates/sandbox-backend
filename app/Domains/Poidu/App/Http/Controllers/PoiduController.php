<?php

namespace App\Domains\Poidu\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoiduController extends Controller
{
    public function ping()
    {
        return config('services.poidu.ping');
    }
}
