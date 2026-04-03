<?php

namespace App\Domains\Poidu\Pipeline\src\Console;

use App\Domains\Poidu\Pipeline\src\Models\EventMining;
use App\Domains\Poidu\Pipeline\src\Models\PipelineEventMining;
use App\Domains\Poidu\Pipeline\src\Traits\Prompts;
use App\Domains\Poidu\Pipeline\src\Traits\Timer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClassifyCommand extends Command
{
    use Timer;
    use Prompts;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:classify {pipelineId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Классифицирует пост';

    private string $url;
    private string $apiKey;
    private Collection|string $dataInput;
    private int $countElementsToPrepare = 0;

    private PipelineEventMining $pipeline;

    private string $inputPath;
    private string $outputPath;

    private function prepare()
    {
        $this->url = config('services.ai.deepseek_url');
        $this->apiKey = config('services.ai.deepseek_api_key');
        $this->dataInput = collect();

        $this->inputPath = "poidu/pipeline/classify/input/";
        $this->outputPath = "poidu/pipeline/classify/output/";

        if ($this->argument('pipelineId')) {
            $this->pipeline = PipelineEventMining::find($this->argument('pipelineId'));
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dump("Начинается извлечение полезных данных");

        $this->prepare();

        // -------------------------------------------------------------+
        // создание структуры данных для передачи в ai                  |
        // на обработку будут переданы только еще необработанные данные |
        // -------------------------------------------------------------+
        $lastSuccessPrepare = PipelineEventMining::where('failed')->max('updated_at');

        EventMining::query()
            ->where('category_id', null)
            ->where('created_at', '>', $lastSuccessPrepare)
            ->chunk(50, function ($posts) {
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

        // ---------------------------------+
        // отправка запроса на обработку ai |
        // ---------------------------------+
        $responses = Http::pool(fn(Pool $pool) => $this->getPools($pool));

        // ---------------------------------+
        // получение и обработка результата |
        // ---------------------------------+
        foreach ($responses as $key => $response) {

            if ($response->successful()) {
                $this->saveOutputToJson($response->object());
            } else {
                dump("Запрос {$key} из пула завершился неудачно");
            }

            $response = $response->object();
            $totalTokens = $response->usage->total_tokens;
            dump("Объём токенов {$totalTokens}");
        }

        $executionTime = $this->end();
        dump("Время обработки заняло $executionTime сек.");

        if ($this->argument('pipelineId')) {
            $this->pipeline->update(['2_classify' => now()]);
        }
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
                        "content" => $this->getPromptForDetails(),
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

        Storage::put($this->inputPath . $name, $content);
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

        Storage::put($this->outputPath . $name, $response);
    }
}
