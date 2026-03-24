<?php

namespace App\Domains\Illustrate\Console;

use App\Domains\Poidu\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use stdClass;

use function Symfony\Component\Clock\now;

class CreatePreview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'illustrate:create-preview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private string $url;
    private string $apiKey;


    /**
     * Execute the console command.
     */
    public function handle()
    {
        // +---------------------+
        // подготовка переменных |
        // +---------------------+
        $this->url = 'https://api.replicate.com/v1/models/black-forest-labs/flux-dev/predictions';
        $this->apiKey = config('services.replicate_api_token');

        // +---------------------------------------------------------------------+
        // сначала происходит наполнение таблицы на основе имеющихся изображений |
        // +---------------------------------------------------------------------+
        $previews = collect(Storage::disk('public')->allFiles('previews/'));
        $previews->each(function ($preview) {
            $imageDetail = $this->getImageDetail($preview);
            try {
                $event = Event::query()
                    ->where('post_id', $imageDetail->postId)
                    ->where('channel_id', $imageDetail->channelId)
                    ->where('preview', null)
                    ->firstOrFail();
            } catch (\Throwable $th) {
                return;
            }

            $preview = Str::after($preview, '/');
            $event->update(['preview' => $preview]);
        });

        // +--------------------------------------------------------------+
        // затем происходит генерация изображений для тех, кому нехватило |
        // будем считать, что промпты у них есть                          |
        // +--------------------------------------------------------------+
        $eventWithouthPreview = Event::query()
            ->where('date_time', '>', now())
            ->whereNot('prompt', null)
            ->where('preview', null)
            ->get();

        $eventWithouthPreview->each(function ($event) {
            $response = $this->sendRequest($event->prompt);

            if (!$response->successful()) {
                $this->warn('Не удалось сгенерировать изображение');
                $this->warn($response->body());
                sleep(12);
                return;
            }

            $preview =  $event->post_id . ':' . $event->channel_id . '.jpg';

            $this->saveImage($response, $preview);

            $event->update(['preview' => $preview]);

            $this->info("Добавлено изображение $preview");

            sleep(12);
        });
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
     * Сохранение изображения. Доступ по пути /storage/previews/
     */
    private function saveImage($response, $name)
    {
        $stream = $response['urls']['stream'];

        $response = Http::get($stream);

        Storage::disk('public')->put('previews/' . $name, $response->resource());
    }

    /**
     * Возвращает результат генерации
     */
    private function sendRequest($prompt)
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
