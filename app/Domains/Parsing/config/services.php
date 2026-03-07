<?php

include storage_path('app/private/parsing/telegram/sources/events_channels.php');
include storage_path('app/private/parsing/telegram/sources/events_comunityes.php');

return [
    'parsing' => [
        'tg' => [
            'api_id' => env('TG_API_ID'),
            'api_hash' => env('TG_API_HASH'),

            'path_to_session' => storage_path('app/private/parsing/telegram/session/api.madeline'),

            'events_channels' => $eventsChannels,
            'events_comunityes' => $eventsComunityes,
        ],

        'vk' => [],
    ],
    'ai' => [
        'deepseek_api_key' => env('DEEPSEEK_API_KEY'),
    ]
];
