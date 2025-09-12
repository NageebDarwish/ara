<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgressLevel;

class ProgressLevelSeeder extends Seeder
{
    public function run()
    {
        $levels = [
              ['name' => 'Level 1', 'hours' => 20,   'words' => 0],
            ['name' => 'Level 2', 'hours' => 50,  'words' => 500],
            ['name' => 'Level 3', 'hours' => 100,  'words' => 1000],
            ['name' => 'Level 4', 'hours' => 200, 'words' => 2000],
            ['name' => 'Level 5', 'hours' => 300, 'words' => 3000],
            ['name' => 'Level 6', 'hours' => 400, 'words' => 4000],
            ['name' => 'Level 7', 'hours' => 500, 'words' => 5000],
            ['name' => 'Level 8', 'hours' => 600, 'words' => 6000],
            ['name' => 'Level 9', 'hours' => 700, 'words' => 7000],
            ['name' => 'Level 10','hours' => 800, 'words' => 8000],
        ];

        foreach ($levels as $level) {
            ProgressLevel::create([
                'name' => $level['name'],
                'watching_hours' => $level['hours'],
                'known_words' => $level['words'],
            ]);
        }
    }
}
