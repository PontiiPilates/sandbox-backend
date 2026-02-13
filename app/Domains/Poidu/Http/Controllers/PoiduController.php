<?php

namespace App\Domains\Poidu\Http\Controllers;

use App\Domains\Poidu\Http\Resources\EventResource;
use App\Domains\Poidu\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoiduController extends Controller
{
    public function ping()
    {
        return config('services.poidu.ping');
    }
    public function search()
    {
        return config('services.poidu.ping');
    }
    public function events(Request $request)
    {
        // $column = $request->input('by');

        // колонки::
        // - по категории column=category   value=1
        // - по дате      column=date       value=12.08.2026
        // - по цене      column=price      value=500

        //  Остановился. на создании запроса для фильтрации


        $events = Event::query()

            ->when($request->by, function ($q, $by) use ($request) {
                $q->where($by, $request->value);
            })
            ->limit(3)
            ->get();

        dd($events);

        // return EventResource::collection();
    }
    public function event()
    {
        return config('services.poidu.ping');
    }
}
