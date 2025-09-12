<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Api\StaticsRepository;
use App\Helpers\ExceptionHandlerHelper;


class StaticsController extends Controller
{
    protected $repository;

    public function __construct(StaticsRepository $repository)
    {
        $this->repository=$repository;
    }

    public function index()
    {
        $data=$this->repository->index();
        return $this->sendResponse($data,'Statics fetched successfully');
    }
}
