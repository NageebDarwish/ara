<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{ConsistencyBadge, LearningBadge, ProgressBadge, SpecialAchievementBadge};
use App\Models\Badge;
class BadgeSeeder extends Seeder
{
    public function run()
    {

        Badge::create([
            'type'=>'progress',
            'name'=>'Star Gazer',
            'series_count'=>'1',
        ]);
         Badge::create([
            'type'=>'progress',
            'name'=>'Lunar Voyager',
            'series_count'=>'10',
        ]);
         Badge::create([
            'type'=>'progress',
            'name'=>'Solar Explorer',
            'series_count'=>'50',
        ]);
         Badge::create([
            'type'=>'progress',
            'name'=>'Celestial Navigator',
            'series_count'=>'100',
        ]);

         Badge::create([
            'type'=>'learning',
            'name'=>'First Light',
            'hours_watched'=>'1',
        ]);

          Badge::create([
            'type'=>'learning',
            'name'=>'Steady Glow',
            'hours_watched'=>'20',
        ]);
          Badge::create([
            'type'=>'learning',
            'name'=>'Rising Dawn',
            'hours_watched'=>'100',
        ]);
          Badge::create([
            'type'=>'learning',
            'name'=>'Radiant Horizon',
            'hours_watched'=>'500',
        ]);
          Badge::create([
            'type'=>'learning',
            'name'=>'Eternal Sun',
            'hours_watched'=>'1000',
        ]);

        Badge::create([
            'type'=>'consistency',
            'name'=>'New Moon',
            'streak_days'=>'3',
        ]);

        Badge::create([
            'type'=>'consistency',
            'name'=>'Week’s Orbit',
            'streak_days'=>'7',
        ]);
        Badge::create([
            'type'=>'consistency',
            'name'=>'Lunar Cycle',
            'streak_days'=>'30',
        ]);
         Badge::create([
            'type'=>'consistency',
            'name'=>'Centennial Orbit',
            'streak_days'=>'100',
        ]);
         Badge::create([
            'type'=>'consistency',
            'name'=>'Solar Year',
            'streak_days'=>'daily',
        ]);

          Badge::create([
            'type'=>'special_achievement',
            'name'=>'First Ray',
            'criteria'=>'Watched a new video within 24 hours of release.',
        ]);
          Badge::create([
            'type'=>'special_achievement',
            'name'=>'Eclipse Viewer',
            'criteria'=>'Watched all Super-Beginner videos.',
        ]);
        Badge::create([
            'type'=>'special_achievement',
            'name'=>'Cultural Explorer',
            'criteria'=>'Completed 50 videos in the “Culture” series.',
        ]);
        Badge::create([
            'type'=>'special_achievement',
            'name'=>'Cultural Ambassador',
            'criteria'=>'For completing 100 videos in the “Culture” series.',
        ]);
         Badge::create([
            'type'=>'special_achievement',
            'name'=>'Eternal Light',
            'criteria'=>'Subscribed to a Premium account.',
        ]);
        
         Badge::create([
            'type'=>'community',
            'name'=>'The First Spark',
            'criteria'=>'First comment.',
        ]);
        
         Badge::create([
            'type'=>'community',
            'name'=>'Constellation Connector',
            'criteria'=>'Received 100 likes On Posts',
        ]);
        
          Badge::create([
            'type'=>'community',
            'name'=>'Desert Star',
            'criteria'=>'Create 50 posts',
        ]);


        // ProgressBadge::insert([
        //     ['name' => 'Star Gazer', 'series_count' => '1', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Lunar Voyager', 'series_count' => '10', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Solar Explorer', 'series_count' => '50', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Celestial Navigator', 'series_count' => '100', 'created_at' => now(), 'updated_at' => now()],
        // ]);

        // LearningBadge::insert([
        //     ['name' => 'First Light', 'hours_watched' => '1', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Steady Glow', 'hours_watched' => '20', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Rising Dawn', 'hours_watched' => '100', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Radiant Horizon', 'hours_watched' => '500', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Eternal Sun', 'hours_watched' => '1000', 'created_at' => now(), 'updated_at' => now()],
        // ]);

        // ConsistencyBadge::insert([
        //     ['name' => 'New Moon', 'streak_days' => '3', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Week’s Orbit', 'streak_days' => '7', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Lunar Cycle', 'streak_days' => '30', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Centennial Orbit', 'streak_days' => '1100', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Solar Year', 'streak_days' => 'daily', 'created_at' => now(), 'updated_at' => now()],
        // ]);

        // SpecialAchievementBadge::insert([
        //     ['name' => 'First Ray', 'criteria' => 'Watched a new video within 24 hours of release.', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Eclipse Viewer', 'criteria' => 'Watched all Super-Beginner videos.', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Cultural Explorer', 'criteria' => 'Completed 50 videos in the “Culture” series.', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Cultural Ambassador', 'criteria' => 'For completing 100 videos in the “Culture” series.', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'Eternal Light', 'criteria' => 'Subscribed to a Premium account.', 'created_at' => now(), 'updated_at' => now()],
        // ]);
    }
}