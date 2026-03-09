<?php

namespace App\Domains\Poidu\Http\Controllers;

use App\Domains\Poidu\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AdminController extends Controller
{
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
