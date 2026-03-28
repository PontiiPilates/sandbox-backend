<?php

namespace App\Domains\Poidu\Pipeline\Console;

use App\Domains\Poidu\Pipeline\Models\EventMining;
use App\Domains\Poidu\Pipeline\Models\PipelineEventMining;
use App\Domains\Poidu\Pipeline\Traits\PipelineLogger;
use Carbon\Carbon;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ParsingTelegramCommand extends Command
{
    use PipelineLogger;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:parsing-telegram {pipelineId : идентификатор пайплайна}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсинг Telegram';

    private $eventsChannels; // каналы для парсинга
    private $eventsComunityes; // сообщества для парсинга

    private $madelineProto; // клиент MadelineProto

    private int $recived = 0; // счётчик полученых постов
    private int $saved = 0; // счётчик сохранённых постов

    private int $maxDays = 10; // дней
    private int $depthLimit = 10; // постов
    private int $pause = 1; // пауза для предотвращения превышения лимитов

    private PipelineEventMining $pipeline;

    private string|null $lastDateCreate;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->prepare();
        $this->createMtProtoClient();

        Log::channel('pipeline')->info("Начинается извлечение постов из Telegram");

        foreach ($this->eventsChannels as $channel) {
            $this->line($channel);

            sleep($this->pause);

            // извлечение постов 
            try {
                $messages = $this->madelineProto->messages->getHistory(array_filter([
                    'peer' => $channel,
                    'limit' => $this->depthLimit,
                    'min_id' => EventMining::where('peer', $channel)->max('post_id'),
                ]));
            } catch (\Throwable $th) {
                $this->warn("Возникла проблема при извлечении контента из канала {$channel}");
                continue;
            }

            $this->saveResult($channel, $messages);
        }

        Log::channel('pipeline')->info("Сохранено $this->saved из полученных $this->recived");

        $this->info("Сохранено $this->saved из полученных $this->recived");

        $this->pipeline->update(['parsing' => now()]);
    }

    private function prepare(): void
    {
        // определение даты, старше которой события будут отброшены
        $this->lastDateCreate = EventMining::max('post_was_created');

        if (!$this->lastDateCreate) {
            $this->lastDateCreate = Carbon::now()->subDays($this->maxDays);
        }

        // выбор пайплайна для работы
        $this->pipeline = PipelineEventMining::find($this->argument('pipelineId'));

        // создание источников для парсинга
        $this->eventsChannels = config('services.parsing.tg.sources.events_channels');
        $this->eventsComunityes = config('services.parsing.tg.sources.events_comunityes');
    }

    private function createMtProtoClient(): void
    {
        // создание конфигурации
        $settings = new AppInfo();
        $settings->setApiId(config('services.parsing.tg.madeline_proto.api_id'));
        $settings->setApiHash(config('services.parsing.tg.madeline_proto.api_hash'));

        // генерация клиента
        try {
            $this->madelineProto = new API(config('services.parsing.tg.madeline_proto.path_to_session'), $settings);
        } catch (\Throwable $th) {
            $this->failed($this->pipeline, 'Ошибка при создании клиента MadelineProto. Вероятно следует удалить сессию и авторизоваться вновь. Или выдать права на запись в лог.' . __LINE__);
            return;
        }

        // установка соединения
        try {
            $this->madelineProto->start();
        } catch (\Throwable $th) {
            $this->failed($this->pipeline, 'Ошибка при создании клиента MadelineProto. Вероятно следует удалить сессию и авторизоваться вновь. Или выдать права на запись в лог.' . __LINE__);
            return;
        }
    }

    private function saveResult($channel, $messages)
    {
        $messages = collect($messages['messages']);

        $messages->each(function ($post) use ($channel) {
            $this->recived++;

            $post = (object) $post;

            // пропуск, если пост не содержит контент
            if (empty($post->message)) {
                return true;
            }

            // пропуск, если пост старше даты последнего извлечения
            if ($post->date <= $this->lastDateCreate) {
                return true;
            }

            $create = EventMining::updateOrCreate([
                'peer_id' => $post->peer_id,
                'post_id' => $post->id,
            ], [
                'peer' => $channel,
                'peer_id' => $post->peer_id,

                'post' => $post->post,
                'post_id' => $post->id,
                'date' => Carbon::parse($post->date),
                'message' => $post->message,

            ]);

            if ($create) {
                $this->saved++;
            }
        });
    }
}
