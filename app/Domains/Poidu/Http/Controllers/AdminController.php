<?php

namespace App\Domains\Poidu\Http\Controllers;

use App\Domains\Poidu\Http\Resources\EventResource;
use App\Domains\Poidu\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function events()
    {
        $events = Event::query()
            ->where('date_time', '>', now('Asia/Krasnoyarsk')->subDays(config('services.poidu.past_days')))
            ->orderBy('date_time')
            ->get();

        return EventResource::collection($events);
    }

    public function public(Request $request)
    {
        $event = Event::findOrFail($request->id)->update([
            'approved' => 1,
        ]);

        if ($event) {
            return response(['data' => ['success' => $event, 'message' => 'Опубликован']], 200);
        } else {
            return response(['data' => ['success' => $event]], 409);
        }
    }

    public function unPublic(Request $request)
    {
        $event = Event::findOrFail($request->id)->update([
            'approved' => 0,
        ]);

        if ($event) {
            return response(['data' => ['success' => $event, 'message' => 'Снят с публикации']], 200);
        } else {
            return response(['data' => ['success' => $event]], 409);
        }
    }
}
