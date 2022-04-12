<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // default user
        \App\Models\User::factory(1)->create(['email' => 'demo@snugfeed.com']);

        \App\Models\Feed::factory(2)->has(Article::factory()->count(3))->create();
    }
}
