<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name'=>'Free',
            'cycle'=>'unlimited',
            'price'=>'0',
            'free_videos'=>true,
            'progress_tracking'=>true,
            'community_forums'=>true,
            'exclusive_videos_added_daily'=>false,
            'no_of_exclusive_videos_added_daily'=>'0',
            'premium_video_series'=>false,
            'ability_to_watch_videos_offline'=>false,
            'is_default'=>true,
        ]);
        Plan::create([
            'name'=>'Premium',
            'cycle'=>'monthly',
            'price'=>'14.99',
            'free_videos'=>true,
            'progress_tracking'=>true,
            'community_forums'=>true,
            'exclusive_videos_added_daily'=>true,
            'no_of_exclusive_videos_added_daily'=>'2',
            'premium_video_series'=>true,
            'ability_to_watch_videos_offline'=>true,
        ]);
        Plan::create([
            'name'=>'Premium',
            'cycle'=>'yearly',
            'price'=>'9.99',
            'free_videos'=>true,
            'progress_tracking'=>true,
            'community_forums'=>true,
            'exclusive_videos_added_daily'=>true,
            'no_of_exclusive_videos_added_daily'=>'2',
            'premium_video_series'=>true,
            'ability_to_watch_videos_offline'=>true,
        ]);
    }
}