<?php

include storage_path('app/private/parsing/telegram/sources/events_channels.php');
include storage_path('app/private/parsing/telegram/sources/events_comunityes.php');

return [
    'parsing' => [
        'tg' => [
            'madeline_proto' => [
                'api_id' => env('TG_API_ID'),
                'api_hash' => env('TG_API_HASH'),

                'path_to_session_check' => 'parsing/telegram/madeline-proto/session',
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
        'deepseek_url' => env('DEEPSEEK_URL'),

        'replicate_api_token' => env('REPLICATE_API_TOKEN'),
        'replicate_model_black_forest_url' => env('REPLICATE_MODEL_BLACK_FOREST_URL'),
    ]
];
