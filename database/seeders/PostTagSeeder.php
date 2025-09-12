<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PostTag;

class PostTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags=[
            'places',
            'culture',
            'daily_life',
            'travel_and_tourism',
            'personal_stories',
            'famous_people',
            '30_day_streak',
            '100_day_streak',
            'history',
            'food',
            '100_input_hours',
        ];
        foreach($tags as $tag)
        {
            PostTag::create([
                'name'=>$tag,
            ]);
        }
    }
}
