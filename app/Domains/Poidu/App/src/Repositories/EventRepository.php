<?php

namespace App\Domains\Poidu\App\src\Repositories;

use App\Domains\Poidu\App\src\Http\Resources\EventResource;
use App\Domains\Poidu\Pipeline\src\Models\EventMining;
use Illuminate\Http\Request;

final class EventRepository
{
    public function getEvents(Request $request)
    {
        $events = EventMining::query()
            ->when($request->column, function ($q, $column) use ($request) {
                $q->whereHas($column, function ($q) use ($request) {
                    $q->where('id', $request->value);
                });
            })
            ->when($request->search, function ($q, $search) use ($request) {
                $q->where('title', 'like', "%$search%");
            })
            ->when($request->sort, function ($q, $sort) use ($request) {
                $q->when($request->direction, function ($q, $direction) use ($request, $sort) {
                    switch ($direction) {
                        case 'asc':
                            $q->orderBy($sort);
                            break;
                        case 'desc':
                            $q->orderByDesc($sort);
                            break;
                    }
                });
            })
            ->where('date_time', '>', now('Asia/Krasnoyarsk')->subDays(config('services.poidu.past_days')))
            ->where('approved', 1)
            ->orderBy('date_time')
            ->get();

        return EventResource::collection($events);
    }

    public function getEvent(Request $request)
    {
        $event = EventMining::find($request->id);

        return new EventResource($event);
    }
}
