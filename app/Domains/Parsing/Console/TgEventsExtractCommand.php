<?php

namespace App\Domains\Parsing\Console;

use App\Domains\Parsing\Models\ExtractHistory;
use App\Domains\Parsing\Models\ExtractTgEvent;
use Carbon\Carbon;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TgEventsExtractCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parsing:tg-events-extract
                            {rubicon? : Не сохранять публикации старше n дней}
                            {--R|refresh : Очистка таблицы с экстракцией}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private Carbon|int $rubicon;
    private int $saved = 0;
    private int $iteration = 0;
    private string $ulid;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->ulid = Str::ulid();

        if ($this->argument('rubicon')) {
            $this->rubicon = Carbon::now()->subDays($this->argument('rubicon'));
            $this->rubicon = $this->rubicon->timestamp;
        }

        $eventsChannels = config('services.parsing.tg.sources.events_channels');
        $eventsComunityes = config('services.parsing.tg.sources.events_comunityes');

        $settings = new AppInfo();
        $settings->setApiId(config('services.parsing.tg.madeline_proto.api_id'));
        $settings->setApiHash(config('services.parsing.tg.madeline_proto.api_hash'));

        if ($this->option('refresh')) {
            ExtractTgEvent::truncate();
        }

        // генерация клиента
        try {
            $madelineProto = new API(config('services.parsing.tg.madeline_proto.path_to_session'), $settings);
        } catch (\Throwable $th) {
            $this->line("Не удалось создать клиент. Вероятное решение: удалить сессию и авторизоваться вновь.");
            return __LINE__;
        }

        // установка соединения/авторизация
        try {
            $madelineProto->start();
        } catch (\Throwable $th) {
            $this->line("Не удалось установить соединение. Вероятное решение: удалить сессию и авторизоваться вновь.");
            return __LINE__;
        }

        foreach ($eventsChannels as $channel) {
            $this->line($channel);

            sleep(1); // предотвращение превышения частоты запросов

            // экстракция
            try {
                $messages = $madelineProto->messages->getHistory(array_filter([
                    'peer' => $channel,
                    'limit' => 10,
                    'min_id' => ExtractTgEvent::where('peer', $channel)->max('post_id'),
                ]));
            } catch (\Throwable $th) {
                $this->warn("Возникла проблема при извлечении контента из канала {$channel}");
                continue;
            }

            // разбор и хранение экстракции
            $messages = collect($messages['messages']);

            $messages->each(function ($post) use ($channel) {
                $this->iteration++;

                $post = (object) $post;

                // пропуск, если пост не содержит контент
                if (empty($post->message)) {
                    return true;
                }

                // пропуск, если пост старше даты рубикона
                if (!empty($this->rubicon) && $post->date <= $this->rubicon) {
                    return true;
                }

                ExtractTgEvent::updateOrCreate([
                    'peer_id' => $post->peer_id,
                    'post_id' => $post->id,
                ], [
                    'peer' => $channel,
                    'peer_id' => $post->peer_id,

                    'post' => $post->post,
                    'post_id' => $post->id,
                    'date' => Carbon::parse($post->date),
                    'message' => $post->message,

                    'source' => "https://{$channel}/{$post->id}",

                    'ulid' => $this->ulid,
                ]);

                $this->saved++;
            });
        }

        ExtractHistory::create([
            'extraction_ulid' => $this->ulid,
            'extraction_date' => Carbon::now(),
            'extraction_type' => 'tg_event_extraxt',
            'count_extraction' => $this->iteration,
            'count_saved' => $this->saved,
        ]);

        $this->info("Сохранено $this->saved постов");
    }
}
