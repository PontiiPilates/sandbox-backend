<?php

namespace App\Domains\Poidu\Pipeline\src\Console;

use App\Domains\Poidu\Pipeline\src\Models\EventMining;
use App\Domains\Poidu\Pipeline\src\Models\PipelineEventMining;
use App\Domains\Poidu\Pipeline\src\Traits\Timer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use stdClass;

class ImagenizeCommand extends Command
{
    use Timer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:imagenize {pipelineId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Наполняет посты имеющимися изображениями. Генерирует новые изображение.';

    private string $url;
    private string $apiKey;

    private int $countElementsToPrepare = 0;

    private PipelineEventMining $pipeline;

    private string $inputPath;
    private string $outputPath;


    private function prepare()
    {
        $this->url = config('services.ai.replicate_model_black_forest_url');
        $this->apiKey = config('services.ai.replicate_api_token');

        $this->outputPath = "previews/";

        if ($this->argument('pipelineId')) {
            $this->pipeline = PipelineEventMining::find($this->argument('pipelineId'));
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dump("Начинается генерация изображений");

        $this->prepare();

        // +---------------------------------------------------------------------+
        // сначала происходит наполнение таблицы на основе имеющихся изображений |
        // +---------------------------------------------------------------------+
        $previews = collect(Storage::disk('public')->allFiles('previews/'));
        $previews->each(function ($preview) {
            $imageDetail = $this->getImageDetail($preview);
            try {
                $eventMining = EventMining::query()
                    ->where('post_id', $imageDetail->postId)
                    ->where('channel_id', $imageDetail->channelId)
                    ->where('preview', null)
                    ->firstOrFail();
            } catch (\Throwable $th) {
                return;
            }

            $preview = Str::after($preview, '/');
            $eventMining->update(['preview' => $preview]);
        });


        $this->start();

        // +-----------------------------------------------------------------------------------------+
        // затем происходит генерация изображений для тех мероприятий, которым нехватило изображений |
        // будем считать, что промпты у них есть                                                     |
        // +-----------------------------------------------------------------------------------------+
        $eventWithouthPreview = EventMining::query()
            ->where('date_time', '>', now())
            ->whereNot('prompt', null)
            ->where('preview', null)
            ->get();

        $count = $eventWithouthPreview->count();
        dump("Обнаружено $count событий без изображения");

        $eventWithouthPreview->each(function ($event) {
            $response = $this->generatePreview($event->prompt);

            if (!$response->successful()) {
                dump('Не удалось сгенерировать изображение', [$response->object()]);
                sleep(12);
                return;
            }

            $preview = $event->post_id . ':' . $event->peer_id . '.jpg';

            $this->saveImage($response, $preview);

            $event->update(['preview' => $preview]);

            dump("Добавлено изображение $preview");

            sleep(12); // todo: добавить в начало цикла для первой итерации
        });

        $executionTime = $this->end();
        dump("Время обработки заняло $executionTime сек.");

        if ($this->argument('pipelineId')) {
            $this->pipeline->update(['6_imagenize' => now()]);
        }
    }

    private function getImageDetail($name)
    {
        $name = Str::after($name, '/');
        $name = Str::before($name, '.');
        $name = explode(':', $name);

        $detail = new stdClass();
        $detail->postId = $name[0];
        $detail->channelId = $name[1];

        return $detail;
    }

    /**
     * Изображения доступны по пути /storage/previews/
     */
    private function saveImage($response, $name)
    {
        $stream = $response['urls']['stream'];

        $response = Http::get($stream);

        Storage::disk('public')->put($this->outputPath . $name, $response->resource());
    }

    /**
     * Возвращает результат генерации
     */
    private function generatePreview($prompt)
    {
        $param = [
            "input" => [
                "prompt" => $prompt,
                "go_fast" => true,
                "guidance" => 3.5,
                "megapixels" => "1",
                "num_outputs" => 1,
                "aspect_ratio" => "4:3",
                "output_format" => "jpg",
                "output_quality" => 80,
                "prompt_strength" => 0.8,
                "num_inference_steps" => 28
            ],
        ];

        return Http::withToken($this->apiKey)->post($this->url, $param);
    }
}
