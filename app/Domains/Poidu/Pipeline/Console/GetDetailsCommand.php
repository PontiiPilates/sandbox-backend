<?php

namespace App\Domains\Poidu\Pipeline\Console;

use App\Domains\Poidu\Pipeline\Models\EventMining;
use App\Domains\Poidu\Pipeline\Models\PipelineEventMining;
use App\Domains\Poidu\Pipeline\Traits\Timer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GetDetailsCommand extends Command
{
    use Timer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:get-details {pipelineId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Возвращает детали мероприятия';

    private string $url;
    private string $apiKey;
    private Collection|string $dataInput;
    private int $countElementsToPrepare = 0;

    private PipelineEventMining $pipeline;


    private function prepare()
    {
        $this->url = config('services.ai.deepseek_url');
        $this->apiKey = config('services.ai.deepseek_api_key');
        $this->dataInput = collect();

        $this->pipeline = PipelineEventMining::find($this->argument('pipelineId'));
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dump("Начинается извлечение полезных данных");

        $this->prepare();

        // создание структуры данных для передачи в ai
        EventMining::where('category_id', null)->chunk(50, function ($posts) {
            $this->dataInput->push($posts->map(function ($post) {
                $this->countElementsToPrepare++;
                return [
                    'message' => $post->message,
                    'peer_id' => $post->peer_id,
                    'post_id' => $post->post_id,
                ];
            }));
        });

        dump("Отправлено {$this->countElementsToPrepare} элементов на обработку");

        $this->start();

        // отправка запроса на обработку ai
        $responses = Http::pool(fn(Pool $pool) => $this->getPools($pool));

        // получение и обработка результата
        foreach ($responses as $key => $response) {

            if ($response->successful()) {
                $this->saveOutputToJson($response->object());
            } else {
                dump("Запрос {$key} из пула завершился неудачно");
            }

            $response = $response->object();
            $totalTokens = $response->usage->total_tokens ?? 'unknown';
            dump("Объём токенов {$totalTokens}");
        }

        $executionTime = $this->end();
        dump("Время обработки заняло $executionTime сек.");

        $this->pipeline->update(['details' => now()]);
    }

    private function getPools(Pool $pool)
    {
        return $this->dataInput->each(function ($chunk) use ($pool) {
            $chunk = json_encode($chunk, JSON_UNESCAPED_UNICODE);

            $parameters = [
                "model" => "deepseek-chat",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => $this->getSystemPrompt(),
                    ],
                    [
                        "role" => "user",
                        "content" => "Коллекция постов: {$chunk}",
                    ]
                ],
                "temperature" => 0.1,
                "stream" => false
            ];

            $this->saveInputToJson($chunk);

            return $pool->timeout(300)->withToken($this->apiKey)->post($this->url, $parameters);
        });
    }

    private function saveInputToJson($content): void
    {
        $name = 'inputData_' . Carbon::now()->format('Y-m-d_H:i:s.u') . '.json';
        sleep(1);

        Storage::put('poidu/pipeline/details/input/' . $name, $content);
    }

    private function saveOutputToJson($response): void
    {
        $name = 'outputData_' . Carbon::now()->format('Y-m-d_H:i:s.u') . '.json';
        sleep(1);

        $response = $response->choices[0]->message->content;

        if (Str::startsWith($response, '```json') && Str::endsWith($response, '```')) {
            $response = Str::remove('```json', $response);
            $response = Str::remove('```', $response);
        }

        Storage::put('poidu/pipeline/details/output/' . $name, $response);
    }

    private function getSystemPrompt(): string
    {
        $date = Carbon::now()->isoFormat('YYYY-MM-DD');

        return <<<PROMPT
        Ты система для обработки данных. Всегда отвечай ТОЛЬКО валидным JSON. Без дополнительного текста, пояснений или markdown.

        Я передаю тебе коллекцию постов. Твоя задача — отфильтровать её, оставив только **анонсы туристических мероприятий**, и преобразовать их в новую JSON-коллекцию согласно схеме ниже.

        ### Критерии отбора (ДОЛЖНЫ выполняться все условия):
        Пост считается анонсом туристического мероприятия, если в поле "message" содержится:
        - Указание на дату проведения (день, месяц), которая позже $date.
        - Указание на время начала или контекстная привязка ко времени суток (утро, вечер).
        - Указание на стоимость (сумма в рублях, слово "бесплатно", "донат" или фраза о том, что участие платное).
        - Четкое описание активности (куда идем, что делаем).

        ### Определение "Туристического мероприятия":
        - **Точно туристические:** поход, сплав, экскурсия, спуск в пещеру (спелео), восхождение, прогулка, приключение, тур, фото-прогулка.
        - **Около туристические:** соревнования на природе, йога на свежем воздухе, плоггинг (спорт+мусор), ориентирование.
        - **Исключения (НЕ брать):** языковые курсы, нетворкинги, бизнес-встречи, мастер-классы в помещениях, спектакли, лекции без выезда на природу.

        ### Категории:
        - Походы
        - Сплавы
        - Экскурсии
        - Туры
        - С детьми
        - Спелео
        - Восхождения
        - Соревнования
        - Фото

        ### Выходная схема (JSON-массив объектов):
        Каждый объект должен содержать следующие поля:

        | Поле | Источник / Правило |
        |------|-------------------|
        | `peer_id` | Скопировать из исходного поля `peer_id` |
        | `post_id` | Скопировать из исходного поля `post_id` |
        | `date_time` | Сформировать на основе `message` в формате `YYYY-MM-DD HH:MM:SS`. Если время не указано — `00:00:00` |
        | `price_min` | Целое число. Если цена не указана — `0`. Если указана одна сумма — продублировать в `price_max` |
        | `price_max` | Целое число. Если цена не указана — `0` |
        | `category` | Основная категория из списка выше |
        | `additional_category` | Дополнительная категория из списка (если применимо), иначе `null` |

        ### Важно:
        - Если пост **не соответствует всем критериям отбора** — НЕ включай его в результат.
        - Результат всегда должен быть JSON-массивом. Если подходящих постов нет — верни `[]`.
        PROMPT;
    }
}
