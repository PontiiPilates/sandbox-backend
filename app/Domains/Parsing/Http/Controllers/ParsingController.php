<?php

namespace App\Domains\Parsing\Http\Controllers;

use App\Domains\Parsing\Http\Resources\ExtractTgEventResource;
use App\Domains\Parsing\Models\ExtractTgEvent;
use App\Http\Controllers\Controller;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;

class ParsingController extends Controller
{
    public function tgAuth()
    {
        // создание конфигурации
        $settings = new AppInfo();
        $settings->setApiId(config('services.parsing.tg.madeline_proto.api_id'));
        $settings->setApiHash(config('services.parsing.tg.madeline_proto.api_hash'));

        // генерация клиента
        try {
            $madelineProto = new API(config('services.parsing.tg.madeline_proto.path_to_session'), $settings);
        } catch (\Throwable $th) {
            dump('Ошибка при создании клиента MadelineProto. Вероятно следует удалить сессию и авторизоваться вновь. Или выдать права на запись в лог.' . __LINE__);
            return;
        }

        // установка соединения
        try {
            $madelineProto->start();
        } catch (\Throwable $th) {
            dump('Ошибка при создании клиента MadelineProto. Вероятно следует удалить сессию и авторизоваться вновь. Или выдать права на запись в лог.' . __LINE__);
            return;
        }

        dd($madelineProto->getSelf());
    }

    public function showExtract()
    {
        $events = ExtractTgEvent::get();

        return ExtractTgEventResource::collection($events);
    }
}
