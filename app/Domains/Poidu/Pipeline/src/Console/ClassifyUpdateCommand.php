<?php

namespace App\Domains\Poidu\Pipeline\src\Console;

use App\Domains\Poidu\App\src\Models\Category;
use App\Domains\Poidu\Pipeline\src\Models\EventMining;
use App\Domains\Poidu\Pipeline\src\Models\PipelineEventMining;
use App\Domains\Poidu\Pipeline\src\Traits\PipelineLogger;
use App\Domains\Poidu\Pipeline\src\Traits\Timer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClassifyUpdateCommand extends Command
{
    use Timer;
    use PipelineLogger;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:classify-update {pipelineId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет классифицированными данными';

    private int $countAddedPosts = 0;

    private PipelineEventMining $pipeline;

    private string $outputPath;

    private function prepare()
    {
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
        dump("Начинается обновление классифицированными данными");
        $this->start();


        $this->prepare();

        $files = Storage::allFiles($this->outputPath);
        $files = collect($files);


        $files->each(function ($file) {
            $json = Storage::json($file);
            $json = collect($json);

            $json->each(function ($post) use ($file) {
                $post = (object) $post;

                // получение сырой записи
                $eventMining = EventMining::where([
                    ['peer_id', '=', $post->peer_id],
                    ['post_id', '=', $post->post_id],
                    ['source_file', '=', null],
                ])->first();

                // если запись не найдена, то переход к следующей итерации
                if (!$eventMining) {
                    return;
                }

                // получение категорий
                $category = Category::where('category', $post->category)->first();
                $additionalCategory = Category::when($post->category, function ($q, $category) use ($post) {
                    $q->where('category', $post->category)->first();
                });

                // обновление полученной записи
                try {
                    $eventMining->update([
                        'date_time' => $post->date_time,
                        'price_min' => $post->price_min,
                        'price_max' => $post->price_max,
                        'category_id' => $category->id,
                        'additional_category' => $additionalCategory,
                        'source_file' => Str::after($file, $this->outputPath),
                    ]);
                } catch (\Throwable $th) {
                    dump("Не удалось обнаружить событие peer_id {$post->peer_id}, post_id {$post->post_id} для обновления");
                    $this->failed($this->pipeline, "Не удалось обнаружить событие peer_id {$post->peer_id}, post_id {$post->post_id} для обновления");
                    return;
                }

                if ($eventMining) {
                    $this->countAddedPosts++;
                }
            });
        });

        dump("Обновлено {$this->countAddedPosts} мероприятий");

        $executionTime = $this->end();
        dump("Время обработки заняло $executionTime сек.");

        if ($this->argument('pipelineId')) {
            $this->pipeline->update(['3_classify_update' => now()]);
        }
    }
}
