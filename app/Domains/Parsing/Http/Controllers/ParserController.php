<?php

namespace App\Domains\Parsing\Http\Controllers;

use App\Http\Controllers\Controller;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Http\Request;

class ParserController extends Controller
{
    public function test() {
        return 'Is ok';
    }

        public function madeline()
    {
        $apiId = '###';
        $apiash = '###';

        $settings = new AppInfo();
        $settings->setApiId($apiId);
        $settings->setApiHash($apiash);




        $madelineProto = new API(
            storage_path('app/private/parsing/telegram/session/api.madeline'),
            $settings
        );

        $madelineProto->start();

        // $me = $madelineProto->getSelf();

        $messges = $madelineProto->messages->getHistory([
            'peer' => 't.me/yogajulja',
            'limit' => 3,
        ]);

        dd($messges);

        // $madelineProto->getMaxAuthTries();

        // dd($madelineProto);
    }
}
