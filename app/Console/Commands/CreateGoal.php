<?php

namespace App\Console\Commands;

use App\Models\Goal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateGoal extends Command
{
    protected $signature = 'goals:create-daily';
    protected $description = 'Create daily goals for all active users based on their latest goal settings';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        // Get all active users who have at least one goal set
        $users = User::whereHas('goals')->get();

        foreach ($users as $user) {
            // Get user's most recent goal
            $latestGoal = $user->goals()
                ->latest('date')
                ->first();

            // Check if today's goal already exists
            $existingGoal = Goal::where('user_id', $user->id)
                ->where('date', $today)
                ->exists();

            if (!$existingGoal && $latestGoal) {
                // Create new goal for today with same settings
                Goal::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'target_minutes' => $latestGoal->target_minutes,
                    'completed_minutes' => 0
                ]);

                $this->info("Created daily goal for user: {$user->id}");
            }
        }

        $this->info('Daily goals creation completed!');
    }
}
