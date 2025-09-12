<?php

namespace App\Repositories\Api;

use App\Models\Goal;
use Carbon\Carbon;
use App\Services\BadgeAssignmentService;

class GoalRepository
{
    private $model;
    private $service;

    public function __construct(Goal $model, BadgeAssignmentService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }

    public function index()
    {
        $date = Carbon::now()->toDateString();  
        return $this->model::where('user_id', auth()->user()->id)->where('date', $date)->first();
    }

    public function store($data)
    {
        $data['user_id']=auth()->id();
        return $this->model->updateOrCreate(
            ['user_id' => $data['user_id'], 'date' => $data['date']],
            ['target_minutes' => $data['target_minutes']]
        );
    }

    public function update($data)
    {
        $user = auth()->user();
        $model=$this->model->where('date', $data['date'])->where('user_id',$user->id)->first();
        $data['completed_minutes'] += $model->completed_minutes;
        $goal= $model->update($data);
        return $goal;
    }

    // public function updateLevel($user)
    // {
    //     $hours = $user->watching_hours / 3600;

    //     if ($hours >= 700) {
    //         $user->progress_level_id = 10;
    //     } elseif ($hours >= 600) {
    //         $user->progress_level_id = 9;
    //     } elseif ($hours >= 500) {
    //         $user->progress_level_id = 8;
    //     } elseif ($hours >= 400) {
    //         $user->progress_level_id = 7;
    //     } elseif ($hours >= 300) {
    //         $user->progress_level_id = 6;
    //     } elseif ($hours >= 200) {
    //         $user->progress_level_id = 5;
    //     } elseif ($hours >= 100) {
    //         $user->progress_level_id = 4;
    //     } elseif ($hours >= 50) {
    //         $user->progress_level_id = 3;
    //     } elseif ($hours >= 20) {
    //         $user->progress_level_id = 2;
    //     } else {
    //         $user->progress_level_id = 1;
    //     }

    //     $user->save();
    // }

    public function streaks()
    {
        $goals = $this->model::where('user_id', auth()->id())
                ->orderBy('date', 'desc')
                ->get();

        if ($goals->isEmpty()) {
            return [];
        }

        $continuousGoals = [];
        $expectedDate = now()->toDateString();

        foreach ($goals as $goal) {
            if ($goal->date == $expectedDate) {
                $continuousGoals[] = $goal;
                $expectedDate = now()->subDays(count($continuousGoals))->toDateString();
            } else {
                break;
            }
        }
        $streakDays = count($continuousGoals);
        $this->service->assignConsistencyBadge($streakDays);
        return $continuousGoals;
    }


    public function showAll()
    {
        return $this->model::where('user_id',auth()->id())->get();
    }


}