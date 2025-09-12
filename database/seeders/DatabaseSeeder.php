<?php

namespace Database\Seeders;

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
        $this->call(UserSeeder::class);
        $this->call(LevelSeeder::class);
        $this->call(ProgressLevelSeeder::class);
        $this->call(BadgeSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(PostTagSeeder::class);
        $this->call(BlogCategorySeeder::class);
    }
}
