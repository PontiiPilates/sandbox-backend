<?php

namespace App\Domains\Parsing\Console;

use App\Domains\Poidu\Models\Category;
use App\Domains\Poidu\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parsing:update-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private int $countAddedPosts = 0;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = Storage::allFiles("parsing/telegram/results");
        $files = collect($files);

        $files->each(function ($file) {
            $json = Storage::json($file);
            $json = collect($json);

            $json->each(function ($post) {
                $post = (object) $post;

                $category = Category::where('category', $post->category)->first();

                Event::updateOrCreate(
                    [
                        'channel_id' => $post->channel_id,
                        'post_id' => $post->post_id,
                    ],
                    [
                        'category_id' => $category->id,
                        'title' => $post->title,
                        'description' => $post->description,
                        'date_time' => $post->date_time,
                        'price_min' => $post->price_min,
                        'price_max' => $post->price_max,
                        'channel' => $post->channel,
                        'link_to_post' => $post->link,
                        'post_was_created' => $post->date,
                        'approved' => 1,
                    ]
                );

                $this->countAddedPosts++;
            });

            $fileName = Str::after($file, 'results/');
            Storage::move($file, "parsing/telegram/deleted/{$fileName}");
        });

        $this->info("Добавлено {$this->countAddedPosts} мероприятий");
    }
}
