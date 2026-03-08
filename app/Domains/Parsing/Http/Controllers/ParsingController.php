<?php

namespace App\Domains\Parsing\Http\Controllers;

use App\Domains\Parsing\Http\Resources\ExtractTgEventResource;
use App\Domains\Parsing\Models\ExtractTgEvent;
use App\Http\Controllers\Controller;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Http\Request;

class ParsingController extends Controller
{
    public function tgAuth()
    {
        $settings = new AppInfo();
        $settings->setApiId(config('services.parsing.tg.api_id'));
        $settings->setApiHash(config('services.parsing.tg.api_hash'));

        $madelineProto = new API(config('services.parsing.tg.path_to_session'), $settings);
        $madelineProto->start();

        $me = $madelineProto->getSelf();

        dd($me);
    }

    public function showExtract()
    {
        $events = ExtractTgEvent::get();

        return ExtractTgEventResource::collection($events);
    }
}
