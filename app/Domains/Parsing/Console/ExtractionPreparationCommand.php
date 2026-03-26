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
        dd();
        
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

            // dd('Stop');
            $responses = Http::pool(fn(Pool $pool) => $this->getPools($pool));

            foreach ($responses as $key => $response) {

                dump($response);

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

            $ulid = Str::ulid();
            $parametersForFile = json_encode($parameters, JSON_UNESCAPED_UNICODE);
            $res = Storage::put('test/' . $ulid . 'param' . '.json', $parametersForFile);
            dump($res);
            $res = Storage::put('test/' . $ulid . '.json', $chunk);
            dump($res);



            // return $pool->timeout(300)->withToken($this->apiKey)->post($this->url, $parameters);
        });
    }

    /**
     * Возвращает промпт
     */
    private function getSystemPrompt(): string
    {
        $date = Carbon::now()->isoFormat('YYYY-MM-DD');

        return <<<PROMPT
        Ты система для работы с данными. Твоя задача - категоризировать посты и создавать к ним промпты для генерации изображений. Всегда отвечай ТОЛЬКО валидным Json. БЕЗ дополнительного текста.

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

        Задача №3 - создать короткий промпт для генерации иллюстрирующего пост изображения.

        Правила:
            - Промпт должен быть на английском языке.
            - Изображение должно быть фотореалистичным.
            - Лейтмотив: походы, экскурсии, сплавы, туры, восхождения на горы, спуск в пещеры.
            - Длина промпта - не более 20 слов.
            - Промпт должен быть создан на основе description

        Задача №4 - сформировать и вернуть НОВЫЙ Json с элементами со следующей структурой:
            - title - заголовок (название мероприятия)
            - description - краткое описание мероприятия
            - date_time - дата и время начала мероприятия в формате dateTime (ISO 8601: YYYY-MM-DD HH:MM:SS)
            - price_min - минимальная стоимость за участие в мероприятии (если о стоимости не говорится, то 0)
            - price_max - максимальная стоимость за участие в мероприятии (если о стоимости не говорится, то 0)
            - category - указать категорию, к которой удалось отнести мероприятие
            - additional_category - указать дополнительную категорию, если таковая присутствует
            - child - true - если в анонсе есть информация о том, что на мероприятие можно с детьми, false - если информации о детях нет
            - prompt - промпт для генерации иллюстрирующего изображения

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
        // Проблема ясна осталось переписать промпт

        // Исходный
        // "id": 164,
        // "peer": "t.me\/turist_jurist",
        // "peer_id": -1001767496452,
        // "post": 1,
        // "post_id": 982,
        // "date": "2026-03-24 05:21:38",
        // "message": "Бодрого дня всем!  \nПриглашаю в пешеходные прогулки выходного дня🏔  Идём, не спешим, наслаждаемся природой.\n\n⛳️ 28 Марта - Суббота\nПоход на ЕСАУЛОВСКУЮ ПЕТЛЮ\n\n     Машинами едем до Бархатово.  Пойдем  обычным маршрутом  до ПЕТЛИ. На обратном пути зайдём на ЦАРСКУЮ горку , тропой Ленина  выйдем к машинам. \n   От поселка  комфортный подъем на ЕСАУЛОВСКУЮ петлю ( 4 км), оставшаяся часть маршрута ( 7 км) будет ещё комфортней, мы с ним легко справимся 💪.\n  С вершин прекрасный панорамный вид на долину реки Есауловка и ее окрестности.\n   🥾 Протяженность пешего маршрута не более 12 км. \n  Погуляем по лесу, подышим чистым воздухом.  Сделаем красивые фотографии.\n  Встречаемся: 09:30   Матросова 3, у магазина Красный Яр.\nВернемся  до 17\n☝В  походе мы не спешим, идём спокойным темпом,  каждый может остановиться в любой момент,  отдохнуть\/полюбоваться видом.\n   Одеваемся по погоде.\n  🏦 Стоимость похода:\nДля взрослых участников 900₽, для детей 500₽ и бесплатно для водителей (если берете 3-4 пассажиров).\nзапись по предоплате\n📞 89048955253.\n\nhttps:\/\/t.me\/turist_jurist\n\nhttps:\/\/max.ru\/join\/jzOxk7JN8F5BLgQkDJN3Zj2SirngshteMx62ImRemk4",
        // "source": "https:\/\/t.me\/turist_jurist\/982",
        // "ulid": "01KMFCVPH3WQV6WZX42K776KDA",
        // "created_at": "2026-03-24T07:45:06.000000Z",
        // "updated_at": "2026-03-24T07:45:06.000000Z"

        // Новый
        // "id": 164,
        // "peer": "t.me/turist_jurist",
        // "peer_id": -1001767496452,
        // "post": 1,
        // "post_id": 982,
        // "date": "2026-03-24 05:21:38",
        // "message": "Бодрого дня всем!  \nПриглашаю в пешеходные прогулки выходного дня🏔  Идём, не спешим, наслаждаемся природой.\n\n⛳️ 28 Марта - Суббота\nПоход на ЕСАУЛОВСКУЮ ПЕТЛЮ\n\n     Машинами едем до Бархатово.  Пойдем  обычным маршрутом  до ПЕТЛИ. На обратном пути зайдём на ЦАРСКУЮ горку , тропой Ленина  выйдем к машинам. \n   От поселка  комфортный подъем на ЕСАУЛОВСКУЮ петлю ( 4 км), оставшаяся часть маршрута ( 7 км) будет ещё комфортней, мы с ним легко справимся 💪.\n  С вершин прекрасный панорамный вид на долину реки Есауловка и ее окрестности.\n   🥾 Протяженность пешего маршрута не более 12 км. \n  Погуляем по лесу, подышим чистым воздухом.  Сделаем красивые фотографии.\n  Встречаемся: 09:30   Матросова 3, у магазина Красный Яр.\nВернемся  до 17\n☝В  походе мы не спешим, идём спокойным темпом,  каждый может остановиться в любой момент,  отдохнуть/полюбоваться видом.\n   Одеваемся по погоде.\n  🏦 Стоимость похода:\nДля взрослых участников 900₽, для детей 500₽ и бесплатно для водителей (если берете 3-4 пассажиров).\nзапись по предоплате\n📞 89048955253.\n\nhttps://t.me/turist_jurist\n\nhttps://max.ru/join/jzOxk7JN8F5BLgQkDJN3Zj2SirngshteMx62ImRemk4",
        // "source": "https://t.me/turist_jurist/982",
        // "ulid": "01KMFCVPH3WQV6WZX42K776KDA",
        // "created_at": "2026-03-24T07:45:06.000000Z",
        // "updated_at": "2026-03-24T07:45:06.000000Z",
        
        // "title": "Поход на Есауловскую петлю",
        // "description": "Пешеходная прогулка на Есауловскую петлю с панорамными видами, поход по лесу, фотографии.",
        // "date_time": "2026-03-28 09:30:00",
        // "price_min": 500,
        // "price_max": 900,
        // "category": "Походы",
        // "additional_category": "Фото",
        // "child": true,
        // "prompt": "A group of hikers enjoying panoramic views from a forested hilltop trail on a sunny day."
