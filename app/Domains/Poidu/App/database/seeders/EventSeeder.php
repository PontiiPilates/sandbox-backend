<?php

namespace App\Domains\Poidu\App\database\seeders;

use App\Domains\Poidu\App\Models\Category;
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
        $max = Category::max('id');

        DB::table('events')->insert([
            'category_id' => rand(1, $max),

            'title' => 'Грядущее мероприятие',
            'description' => 'Описание грядущего мероприятия',

            'date_time' => now()->addDays(3),

            'price_min' => rand(300, 900),
            'price_max' => rand(1000, 5900),

            'channel' => 't.me/source',
            'channel_id' => -1001767496452,
            'post_id' => rand(999, 9999),
            'link_to_post' => 'https://t.me/source/10"',
            'post_was_created' => now()->subHours(3),

            'approved' => 1,
            'views' => rand(10, 99),

            'updated_at' => now(),
            'created_at' => now(),
        ]);

        DB::table('events')->insert([
            'category_id' => rand(1, $max),

            'title' => 'Прошедшее мероприятие',
            'description' => 'Описание прошедшего мероприятия',

            'date_time' => now()->subDays(3),

            'price_min' => rand(300, 900),
            'price_max' => rand(1000, 5900),

            'channel' => 't.me/source',
            'channel_id' => -1001767496452,
            'post_id' => rand(999, 9999),
            'link_to_post' => 'https://t.me/source/10"',
            'post_was_created' => now()->subHours(3),

            'approved' => 1,
            'views' => rand(100, 999),

            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }
}
