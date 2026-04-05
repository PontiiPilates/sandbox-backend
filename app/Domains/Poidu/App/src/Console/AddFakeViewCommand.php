<?php

namespace App\Domains\Poidu\App\src\Console;

use App\Domains\Poidu\Pipeline\src\Models\EventMining;
use Illuminate\Console\Command;

class AddFakeViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poidu:add-fake-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = EventMining::where('date_time', '>', now('Asia/Krasnoyarsk')->subDays(config('services.poidu.past_days')))->get();
        
        $fakeViews = [
            1,1,1,1,1,1,1,1,1,1,
            2,2,2,
            3,3,
            5,
            8,
            13,
        ];

        $events->each(function ($event) use ($fakeViews) {
            $views = $event->views;

            $event->update([
                'views' => $views + $fakeViews[array_rand($fakeViews)],
            ]);
        });
    }
}
