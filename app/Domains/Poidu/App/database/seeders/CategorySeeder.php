<?php

namespace App\Domains\Poidu\App\database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catrgories = [
            'hiking' => 'Походы',
            'water' => 'Сплавы',
            'excursions' => 'Экскурсии',
            'tours' => 'Туры',
            'child' => 'C детьми',
            'weekend' => 'Эти выходные',
            'speleo' => 'Спелео',
            'mountains' => 'Восхождения',
            'tournaments' => 'Соревнования',
            'photo' => 'Фото'
        ];

        foreach ($catrgories as $key => $value) {
            DB::table('categories')->insert([
                'alias' => $key,
                'name' => $value,
            ]);
        }
    }
}
