<?php

namespace App\Domains\Poidu\Pipeline\Console;

use App\Domains\Poidu\Pipeline\Models\EventMining;
use App\Domains\Poidu\Pipeline\Models\PipelineEventMining;
use App\Domains\Poidu\Pipeline\Traits\Timer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UpdatePromptCommand extends Command
{
    use Timer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:update-prompt {pipelineId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет новыми деталями';

    private string $inputPath;
    private string $outputPath;

    private int $countAddedPosts = 0;

    private PipelineEventMining $pipeline;

    private function prepare()
    {
        $this->inputPath = "poidu/pipeline/prompt/input/";
        $this->outputPath = "poidu/pipeline/prompt/output/";

        if ($this->argument('pipelineId')) {
            $this->pipeline = PipelineEventMining::find($this->argument('pipelineId'));
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dump("Начинается добавление промптов");
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
                    ['prompt', '=', null],
                ])->first();

                // если запись не найдена, то переход к следующей итерации
                if (!$eventMining) {
                    return;
                }

                // обновление полученной записи
                try {
                    $eventMining->update([
                        'peer_id' => $post->peer_id,
                        'post_id' => $post->post_id,
                        'title' => $post->title,
                        'description' => $post->description,
                        'prompt' => $post->prompt,
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
            $this->pipeline->update(['update_prompt' => now()]);
        }
    }
}
