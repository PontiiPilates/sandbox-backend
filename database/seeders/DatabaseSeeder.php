<?php

namespace Database\Seeders;

use App\Domains\Poidu\database\seeders\CategorySeeder;
use App\Domains\Poidu\database\seeders\EventSeeder;
use App\Enums\Environment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([CategorySeeder::class]);

        if (env('APP_ENV') === Environment::LOCAL->value) {
            $this->call([EventSeeder::class]);
        }
    }
}
