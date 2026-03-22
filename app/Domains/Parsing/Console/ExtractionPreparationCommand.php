<?php

namespace App\Domains\Parsing\Console;

use App\Domains\Parsing\Http\Resources\ExtractTgEventResource;
use App\Domains\Parsing\Models\ExtractHistory;
use App\Domains\Parsing\Models\ExtractTgEvent;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Symfony\Component\Clock\now;

class ExtractionPreparationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parsing:extraction-preparation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private string $url;
    private string $apiKey;
    private Collection|string $unprepareContent;
    private int $countElementsToPrepare = 0;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->url = 'https://api.deepseek.com/chat/completions';
        $this->apiKey = config('services.ai.deepseek_api_key');

        // получение ulidов необработанных экстракций
        $unPreparedUlids = ExtractHistory::where('prepared_date', null)->get();
        $unPreparedUlids = $unPreparedUlids->pluck('extraction_ulid');

        // формирование необработанного контента в виде чанков
        foreach ($unPreparedUlids as $ulid) {
            $this->unprepareContent = collect();
            $this->countElementsToPrepare = ExtractTgEvent::where('ulid', $ulid)->count();

            ExtractTgEvent::where('ulid', $ulid)->chunk(50, function (Collection $posts) {
                $this->unprepareContent->push($posts);
            });

            $timeStart = Carbon::now();
            $this->info("Отправлено {$this->countElementsToPrepare} элементов на обработку");

            $responses = Http::pool(fn(Pool $pool) => $this->getPools($pool));

            foreach ($responses as $key => $response) {

                if ($response->successful()) {
                    $this->saveToJson($response->object());
                } else {
                    $this->warn("Запрос {$key} из пула завершился неудачно");
                }
            }

            $timeEnd = Carbon::now();
            $executionTime = $timeStart->diffInSeconds($timeEnd);
            $executionTime = number_format((float) $executionTime, 1, '.');

            $this->updateExtractHisory($ulid);

            $this->info("Время обработки заняло $executionTime сек.");
        }
    }

    /**
     * Закрытие записи о необработанной группе
     */
    private function updateExtractHisory($ulid): void
    {
        ExtractHistory::where('extraction_ulid', $ulid)->update([
            'prepared_date' => now(),
            'prepared_ulid' => Str::ulid(),
        ]);
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

        Storage::put("parsing/telegram/results/digest_{$postfix}.json", $response);

        sleep(1);

        $this->line("Обработка пакета заняла {$totalTokens} токенов");
    }

    /**
     * Возвращает pool для параллельной отправки запросов
     */
    private function getPools(Pool $pool)
    {
        return $this->unprepareContent->map(function ($chunk) use ($pool) {
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

            return $pool->timeout(300)->withToken($this->apiKey)->post($this->url, $parameters);
        });
    }

    /**
     * Возвращает промпт
     */
    private function getSystemPrompt(): string
    {
        $date = Carbon::now()->isoFormat('YYYY-MM-DD');

        return <<<PROMPT
        Ты система для категоризации данных. Всегда отвечай ТОЛЬКО валидным Json. БЕЗ дополнительного текста.

        Задача №1 - определить, является ли пост АНОНСОМ ТУРИСТИЧЕСКОГО мероприятия.

        Анонсом является пост, контент которого ("message") содержит:
            1. Дату начала мероприятия (больше чем сегодня $date)
            2. Время начала мероприятия
            3. Стоимость участия в мероприятии (сумма в рублях, бесплатно или за донат)
            4. Краткое или ёмкое описание предстоящего события

        Туристическим мероприятием является:
            1. Точно туристические: поход, сплав, экскурсия, спуск в пещеру, восхождение на гору, прогулка, в том числе с акцентом на фото, приключение, тур т.п.
            2. Около туристические: соревнование, ярмарка, йога на свежем воздухе и т.п.

        Не является туристическим мероприятием: список анонсов, языковой курс, нетворкинг, бизнес-встреча, мастер-класс, спектакль и т.п.

        Задача №2 - если мероприятие туристическое, то отнести его к одной из следующих категорий:
            - Походы
            - Сплавы
            - Экскурсии
            - Туры
            - C детьми
            - Спелео
            - Восхождения
            - Соревнования
            - Фото

        В анонсе может говориться о походе + фото или тур + экскурсия, тогда пусть у мероприятия будет дополнительная категория помимо основной.

        Задача №3 - сформировать и вернуть Json с элементами со следующей структурой:
            - title - заголовок (название мероприятия)
            - description - краткое описание мероприятия
            - date_time - дата и время начала мероприятия в формате dateTime (ISO 8601: YYYY-MM-DD HH:MM:SS)
            - price_min - минимальная стоимость за участие в мероприятии (если о стоимости не говорится, то 0)
            - price_max - максимальная стоимость за участие в мероприятии (если о стоимости не говорится, то 0)
            - category - указать категорию, к которой удалось отнести мероприятие
            - additional_category - указать дополнительную категорию, если таковая присутствует
            - child - true - если в анонсе есть информация о том, что на мероприятие можно с детьми, false - если информации о детях нет

        Добавить к структуре без изменения параметры элемента переданной на обработку коллекции:
            - date
            - channel
            - channel_id
            - post_id
            - link

        Если мероприятие небыло определено как анонс туристического мероприятия, то его следует ИГНОРИРОВАТЬ и НЕ добавлять в Json-коллекцию.
        PROMPT;
    }
}
