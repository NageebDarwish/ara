<?php

namespace App\Repositories\Api;

use App\Models\OutsidePlatformHours;

class OutsidePlatFormRepository
{
    private $model;

    public function __construct(OutsidePlatformHours $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $records = $this->model::where('user_id', auth()->user()->id)->get();
        $totalDuration = $this->model::where('user_id', auth()->user()->id)->sum('duration');
        $result = [
            'records' => $records,
            'total_duration' => $totalDuration
        ];

        return $result;
    }

    public function store($data)
    {
        $user = auth()->user();
        $data['user_id'] = $user->id;
        $data = $this->model::create($data);
        if($data)
        {
            $user->total_watching_hours += $data['duration'];
            $user->watching_hours += $data['duration'];
            $user->save();
        }
    }

    public function update($data, $id)
    {
        
    }
    
    public function delete($id)
    {
        $data = $this->model::findOrFail($id);
        if($data)
        {
            $user = auth()->user();
            $user->total_watching_hours -= $data->duration;
            $user->watching_hours -= $data->duration;
            $user->save();
        }
        return $data->delete();
    }
}