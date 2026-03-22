<?php

namespace App\Domains\Illustrate\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AddIllustratePrompt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'illustrate:add-prompt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private string $url;
    private string $apiKey;
    private array $data;
    private int $countElementsToPrepare = 0;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // объявление переменных
        $this->url = 'https://api.deepseek.com/chat/completions';
        $this->apiKey = config('services.ai.deepseek_api_key');

        // получение из всех источников
        $files = collect(Storage::allFiles('parsing/telegram/deleted/'));
        $files = $files->merge(collect(Storage::allFiles('parsing/telegram/results/')));

        // создание данных на генерацию промптов
        $files->each(function ($path) {
            $json = collect(Storage::json($path));
            $json->each(function ($event) use ($path) {
                if (Carbon::now()->lt($event['date_time'])) {
                    $this->data[] = [
                        'path' => $path,
                        'description' => $event['description'],
                        'channel_id' => $event['channel_id'],
                        'post_id' => $event['post_id'],
                    ];
                }
            });
        });
        $this->countElementsToPrepare = count($this->data);

        $timeStart = Carbon::now();
        $this->info("Отправлено {$this->countElementsToPrepare} элементов на обработку");

        // отправка запроса на генерацию промптов
        $response = $this->sendRequest();

        $timeEnd = Carbon::now();
        $executionTime = $timeStart->diffInSeconds($timeEnd);
        $executionTime = number_format((float) $executionTime, 1, '.');

        // обработка и хранение результата
        if ($response->successful()) {
            $this->saveToJson($response->object());
        } else {
            $this->warn("Запрос неудачно неудачно завершен");
        }
    }

    /**
     * Отправка запроса
     */
    private function sendRequest()
    {
        $data = json_encode($this->data, JSON_UNESCAPED_UNICODE);

        $param = [
            "model" => "deepseek-chat",
            "messages" => [
                [
                    "role" => "system",
                    "content" => $this->getSystemPrompt(),
                ],
                [
                    "role" => "user",
                    "content" => "Коллекция постов: {$data}",
                ]
            ],
            "temperature" => 0.1,
            "stream" => false
        ];

        return Http::timeout(300)->withToken($this->apiKey)->post($this->url, $param);
    }

    /**
     * Сохранение полученного результата в файл
     */
    private function saveToJson($response): void
    {
        $postfix = Carbon::now()->format('Y-m-d_H:i:s.u');

        $totalTokens = $response->usage->total_tokens;

        $response = $response->choices[0]->message->content;
        $response = Str::remove('```json', $response);
        $response = Str::remove('```', $response);

        Storage::put("illustrate/generated/prompts_{$postfix}.json", $response);

        sleep(1);

        $this->line("Обработка пакета заняла {$totalTokens} токенов");
    }

    /**
     * Возвращает промпт
     */
    private function getSystemPrompt(): string
    {
        return <<<PROMPT
        Ты - ассистент по созданию промптов для нейросетей генерации изображений (black-forest-labs/flux-dev). Всегда отвечай ТОЛЬКО валидным Json. БЕЗ дополнительного текста.

        Задача №1 - по параметру description сгенерировать промпт для генерации иллюстрирующего изображения.

        Правила:
            - Промпт должен быть на английском языке.
            - Изображение должно быть фотореалистичным.
            - Лейтмотив: походы, экскурсии, сплавы, туры, восхождения на горы, спуск в пещеры.
            - Длина промпта - не более 20 слов.

        Задача №2 - сформировать и вернуть Json с элементами со следующей структурой:
            - prompt - промпт, который ты сгенерировал
            - channel_id - оставить без изменений (служебная информация)
            - post_id - оставить без изменений (служебная информация)
        PROMPT;
    }
}
