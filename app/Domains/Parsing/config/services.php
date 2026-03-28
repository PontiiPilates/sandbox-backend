<?php

include storage_path('app/private/parsing/telegram/sources/events_channels.php');
include storage_path('app/private/parsing/telegram/sources/events_comunityes.php');

return [
    'parsing' => [
        'tg' => [
            'madeline_proto' => [
                'api_id' => env('TG_API_ID'),
                'api_hash' => env('TG_API_HASH'),
                'path_to_session' => storage_path('app/private/parsing/telegram/madeline-proto/session'),
            ],
            'sources' => [
                'events_channels' => $eventsChannels,
                'events_comunityes' => $eventsComunityes,
            ],
        ],

        'vk' => [],
    ],
    'ai' => [
        'deepseek_api_key' => env('DEEPSEEK_API_KEY'),
    ]
];
