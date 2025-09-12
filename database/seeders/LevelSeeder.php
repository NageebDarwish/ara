<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;


class LevelSeeder extends Seeder
{
    public function run()
    {
         $levels = ['Beginner',  'Intermediate', 'Advance'];
        foreach ($levels as $key => $level) {
            Level::create([
                'name' => $level
            ]);
        }
        
    }
}