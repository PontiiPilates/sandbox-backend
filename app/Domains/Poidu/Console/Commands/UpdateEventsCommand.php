<?php

namespace App\Domains\Poidu\Console\Commands;

use App\Domains\Poidu\Models\Category;
use App\Domains\Poidu\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UpdateEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Исключения парсинга.
     * 
     * @var array
     */
    protected $parsingExceptions = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $digest = Storage::disk('local')->json('poidu/uploads/digest.json');

        $countCategories = Category::count();

        $bar = $this->output->createProgressBar(count($digest));

        $iterable = 0;
        foreach ($digest as $event) {

            $event = (object) $event;

            if (!$event?->title) {
                $this->parsingExceptions[] = $event;
                continue;
            }

            $eventModel = Event::firstOrCreate(
                [
                    'channel_id' => $event->channel_id,
                    'post_id' => $event->post_id,
                ],
                [
                    'category_id' =>  config('services.poidu.fake') ? rand(1, $countCategories) : $event->category,
                    'title' => $event->title,
                    'description' => $event->description,
                    'date_start' => $event->date_start,
                    'time_start' => $event->time_start,
                    'price_min' => $event->price,
                    'price_max' => $event->price,
                    'channel' => $event->channel,
                    'link_to_post' => $event->link,
                    'post_was_created' => $event->date,
                ]
            );

            if ($eventModel->wasRecentlyCreated) {
                $iterable++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("Добавлено $iterable записей");

        if ($this->parsingExceptions) {
            $this->warn("Обнаружены следующие исключения при добавлении");
            dd($this->parsingExceptions);
        }
    }
}
