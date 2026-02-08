<?php

namespace App\Domains\Poidu\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoiduController extends Controller
{
    public function test() {
        return config('services.poidu.some_token');
    }
}
