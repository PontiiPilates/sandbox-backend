<?php

namespace App\Domains\Poidu\App\src\Http\Controllers;

use App\Domains\Poidu\App\src\Http\Resources\EventMiningResource;
use App\Domains\Poidu\App\src\Http\Resources\EventResource;
use App\Domains\Poidu\Pipeline\src\Models\EventMining;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // --------+
    // для api |
    // --------+
    public function events()
    {
        $events = EventMining::query()
            ->where('date_time', '>', now('Asia/Krasnoyarsk')->subDays(config('services.poidu.past_days')))
            ->orderBy('date_time')
            ->get();

        return EventMiningResource::collection($events);
    }

    public function public(Request $request)
    {
        $event = EventMining::findOrFail($request->id)->update([
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
        $event = EventMining::findOrFail($request->id)->update([
            'approved' => 0,
        ]);

        if ($event) {
            return response(['data' => ['success' => $event, 'message' => 'Снят с публикации']], 200);
        } else {
            return response(['data' => ['success' => $event]], 409);
        }
    }

    // -------------+
    // для frontend |
    // -------------+
    public function tgAuth()
    {
        $apiId = config('services.parsing.tg.madeline_proto.api_id');
        $apiHash = config('services.parsing.tg.madeline_proto.api_hash');
        $pathToSessionCheck = config('services.parsing.tg.madeline_proto.path_to_session_check');
        $pathToSession = config('services.parsing.tg.madeline_proto.path_to_session');

        // если нет пути для хранения сессии - он будет создан
        if (!Storage::directoryExists($pathToSessionCheck)) {
            Storage::makeDirectory($pathToSessionCheck);
        }

        $settings = new AppInfo();
        $settings->setApiId($apiId);
        $settings->setApiHash($apiHash);

        $madelineProto = new API($pathToSession, $settings);

        $madelineProto->start();

        dd($madelineProto->getSelf());
    }

    public function published()
    {
        $events = EventMining::query()
            ->where('date_time', '>', now('Asia/Krasnoyarsk')->subDays(config('services.poidu.past_days')))
            ->where('approved', 1)
            ->orderByDesc('updated_at')
            ->get();

        return view('poidu::pages.published', [
            'events' => EventMiningResource::collection($events)->resolve(),
            'seo' => [
                'title' => 'Публикация',
                'description' => 'Снял/поставил'
            ],
        ]);
    }
}
