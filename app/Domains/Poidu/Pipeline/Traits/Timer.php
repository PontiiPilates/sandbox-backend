<?php

namespace App\Domains\Poidu\Pipeline\Traits;

use Illuminate\Support\Carbon;

trait Timer
{
    private $timeStart;

    public function start()
    {
        $this->timeStart = Carbon::now();
    }

    public function end()
    {
        $timeEnd = Carbon::now();
        $executionTime = $this->timeStart->diffInSeconds($timeEnd);
        return number_format((float) $executionTime, 1, '.');
    }
}
