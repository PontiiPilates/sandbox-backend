<?php

namespace App\Domains\Poidu\App\src\Http\Controllers;

use App\Domains\Poidu\App\src\Http\Requests\EventRequest;
use App\Domains\Poidu\App\src\Http\Requests\EventsRequest;
use App\Domains\Poidu\App\src\Http\Resources\EventResource;
use App\Domains\Poidu\App\src\Models\Event;
use App\Domains\Poidu\App\src\Repositories\EventRepository;
use App\Domains\Poidu\Pipeline\src\Models\EventMining;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function __construct(
        private EventRepository $eventRepository,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(EventsRequest $request)
    {
        return $this->eventRepository->getEvents($request);
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
        return $this->eventRepository->getEvent($request);
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
