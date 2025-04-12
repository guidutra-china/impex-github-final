<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@admin.com',
        ]);

        Company::factory()
            ->count(10)
            ->create();

        $tags = ['Flood Light', 'Drivers', 'Street Light'];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}
