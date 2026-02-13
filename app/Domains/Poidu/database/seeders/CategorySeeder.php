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
            'С детьми',
            'Эти выходные',
        ];

        foreach ($catrgories as $category) {
            DB::table('categories')->insert([
                'category' => $category,
            ]);
        }
    }
}
