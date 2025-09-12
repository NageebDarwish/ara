<?php

namespace App\Repositories\Api;

use App\Models\SeriesList;



class SeriesListRepository
{
    private $model;

    public function __construct(SeriesList $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return auth()->user()->seriesLists()->with('series.videos')->get();
    }

    public function store($id)
    {
        $user = auth()->user();

        $user->seriesLists()->updateOrCreate([
            'series_id' => $id,
        ]);

        return true;
    }



}