<?php

namespace App\Domains\Poidu\Http\Controllers;

use App\Domains\Poidu\Http\Requests\EventRequest;
use App\Domains\Poidu\Http\Requests\EventsRequest;
use App\Domains\Poidu\Http\Resources\EventResource;
use App\Domains\Poidu\Models\Event;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EventsRequest $request)
    {
        $events = Event::query()
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
            ->where('date_start', '>', now('Asia/Krasnoyarsk')->subDays(config('services.poidu.past_days')))
            ->get();

        return EventResource::collection($events);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EventRequest $request)
    {
        $event = Event::find($request->id);

        return new EventResource($event);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
