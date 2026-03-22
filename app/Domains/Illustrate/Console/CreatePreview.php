<?php

namespace App\Domains\Illustrate\Console;

use App\Domains\Illustrate\Models\Illustrate;
use App\Domains\Poidu\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
     * todo: отрефакторить вообще всё
     * todo: это обусловлено неработающей базой
     */
    public function handle()
    {
        // подготовка переменных
        $this->url = 'https://api.replicate.com/v1/models/black-forest-labs/flux-dev/predictions';
        $this->apiKey = config('services.replicate_api_token');

        // обход всех файлов
        $files = Storage::allFiles('illustrate/generated');
        foreach ($files as $path) {

            $prompts = Storage::json($path);
            $newData = [];
            foreach ($prompts as $key => $prompt) {
                $illustrateName = Str::ulid();

                // если фото еще не сгенерировано
                if (!isset($prompt['stream'])) {

                    // генерация фото
                    $response = $this->sendRequest($prompt['prompt']);

                    if (!$response->successful()) {
                        $this->warn('Не удалось сгенерировать изображение');
                        $this->warn($response->body());
                        sleep(1);
                        continue;
                    }
                    sleep(1);

                    $linkAphoto = $response['urls']['stream'];

                    // сохранение фото
                    $response = Http::get($linkAphoto);

                    // /storage/previews/someImage.jpg
                    $result = Storage::disk('public')->put('previews/' . $illustrateName . '.jpg', $response->resource());

                    // запись об этом в базу данных
                    $event = Event::query()
                        ->where('channel_id', $prompt['channel_id'])
                        ->where('post_id', $prompt['post_id'])
                        ->first();

                    Illustrate::create([
                        'event_id' => $event->id,
                        'prompt' => $prompt['prompt'],
                        'name' => $illustrateName,
                    ]);

                    // формирование данных с новой информацией для изменения исходного файла
                    $prompt['stream'] = 'link';
                    $prompt['name'] = $illustrateName;
                    $newData[] = $prompt;

                    continue;
                }

                $newData[] = $prompt;
            }

            // обновление исходного файла
            $newData = json_encode($newData, JSON_UNESCAPED_UNICODE);
            $res = Storage::put($path, $newData);

            // финальная проверка исходного файла
            foreach ($prompts as $key => $prompt) {
                $allGenerated = true;
                if (!isset($prompt['stream'])) {
                    $allGenerated = false;
                }
            }

            // перемещение в готовые если всё ок
            if ($allGenerated) {
                $to = Str::replace('generated', 'completed', $path);
                $res = Storage::move($path, $to);
            }
        }
    }

    /**
     * Возвращает pool для параллельной отправки запросов
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
