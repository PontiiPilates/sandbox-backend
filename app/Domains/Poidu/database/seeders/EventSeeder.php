<?php

namespace App\Domains\Poidu\database\seeders;

use App\Domains\Poidu\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            'category_id' => '1',

            'title' => 'Мероприятие',
            'description' => 'Описание мероприятия',

            'date_start' => '2026-03-01',
            'time_start' => '10:00',

            'price_min' => '500',
            'price_max' => '1000',

            'channel' => 't.me/source',
            'channel_id' => 123456789,
            'post_id' => '123456',
            'link_to_post' => 'https://t.me/source/10"',
            'post_was_created' => '2026-02-01 00:00:00+00:00',
        ]);
    }
}
