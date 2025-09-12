<?php

namespace App\Repositories\Api;

use App\Models\Subscription;
use Illuminate\Support\Facades\Storage;
use App\Helpers\UploadFiles;
use App\Models\Plan;
use App\Models\CardDetail;
use App\Models\User;
use App\Services\BadgeAssignmentService;

class SubscriptionRepository
{
    private $model;
    private $badgeService;

    public function __construct(Subscription $model,BadgeAssignmentService $badgeService)
    {
        $this->model = $model;
        $this->badgeService=$badgeService;
    }


    public function create(array $data)
    {
        $plan = Plan::findOrFail($data['plan_id']);
        $cycle = $plan->cycle;
        $user=User::find($data['user_id']);
        $data['status'] = 'active';
        $data['start_date'] = now();

        if ($cycle === 'monthly') {
            $data['end_date'] = now()->addMonth();
        } elseif ($cycle === 'yearly') {
            $data['end_date'] = now()->addYear();
        } else {
            $data['end_date'] = now()->addMonth();
        }
        // CardDetail::updateOrCreate(
        //     ['user_id'=>$data['user_id']],
        //     $data
        // );


        $subscription= $this->model->updateOrCreate(
            ['user_id'=>$data['user_id']],
            $data
        );

        if($subscription)
        {
            $user->update([
                'is_premium'=>true,
            ]);
            $this->badgeService->assignSpecialAchievementBadge('Eternal Light');
        }

        return $subscription;
    }


}