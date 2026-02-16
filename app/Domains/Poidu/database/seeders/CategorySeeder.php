<?php

namespace App\Domains\Poidu\database\seeders;

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
            'Походы',
            'Сплавы',
            'Экскурсии',
            'Туры',
            'C детьми',
            'Эти выходные',
            'Спелео',
            'Восхождения',
            'Соревнования',
            'Фото'
        ];

        foreach ($catrgories as $category) {
            DB::table('categories')->insert([
                'category' => $category,
            ]);
        }
    }
}
