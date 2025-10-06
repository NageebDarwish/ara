<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VideoRequest;
use App\Repositories\Admin\VideoRepository;
use Illuminate\Http\Request;
use App\Models\{Topic, Guide, Level, Video};
use App\Models\Country;
use Exception;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    protected $repository;

    public function __construct(VideoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.modules.video.index');
    }

    public function getVideosData(Request $request)
    {
        $plan = $request->get('plan');
        $videos = $this->repository->getVideosForDataTable($plan);

        return \Yajra\DataTables\Facades\DataTables::of($videos)
            ->addIndexColumn()
            ->addColumn('publishedDate', function ($video) {
                return \Carbon\Carbon::parse($video->scheduleDateTime ?? $video->publishedAt)->format('Y-m-d H:i:s');
            })
            ->addColumn('level', function ($video) {
                return ucfirst($video->level?->name ?? 'N/A');
            })
            ->addColumn('topic', function ($video) {
                return $video->topic?->name ?? 'N/A';
            })
            ->addColumn('guide', function ($video) {
                return $video->guide?->name ?? 'N/A';
            })
            ->addColumn('plan_badge', function ($video) {
                $badges = [
                    'new' => '<span class="badge badge-info">New</span>',
                    'free' => '<span class="badge badge-warning">Free</span>',
                    'premium' => '<span class="badge badge-success">Premium</span>',
                ];
                return $badges[$video->plan] ?? '<span class="badge badge-secondary">' . ucfirst($video->plan) . '</span>';
            })
            ->addColumn('video_link', function ($video) {
                return '<button class="btn btn-sm btn-primary" onclick="openVideoModal(\'' . $video->video . '\', \'' . addslashes($video->title) . '\')">Watch Video</button>';
            })
            ->addColumn('actions', function ($video) {
                $actions = '<a href="' . route('admin.video.edit', $video->id) . '" class="btn btn-sm btn-warning me-2" title="Edit Video"><i class="fa fa-edit"></i></a>';
                if (Auth::check() && Auth::user()->role === 'admin') {
                    $actions .= '<form action="' . route('admin.video.destroy', $video->id) . '" method="POST" style="display:inline-block;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger delete-btn" title="Delete Video"><i class="fa fa-trash"></i></button>
                    </form>';
                }
                return $actions;
            })
            ->rawColumns(['plan_badge', 'video_link', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $topics = Topic::all();
        $guides = Guide::all();
        $levels = Level::all();
        $countries = Country::all();
        return view('admin.modules.video.create', compact('topics', 'countries', 'guides', 'levels'));
    }

    public function store(VideoRequest $request)
    {
        $data = $request->validated();
        try {
            $this->repository->create([$data]);
            return redirect()->route('admin.video.index')->with('success', 'Video created successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.video.index')->with('error', 'Failed to create video: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $video = $this->repository->find($id);
        $topics = Topic::all();
        $guides = Guide::all();
        $levels = Level::all();
        $countries = Country::all();

        return view('admin.modules.video.edit', compact('video', 'topics', 'countries', 'levels', 'guides'));
    }
    public function update(Request $request, $id)
    {
        $video = $this->repository->find($id);
        $data = $request->all();
        try {
            // if ($video->video) {
            //     $this->repository->updateVideoOnYouTube(
            //         $video->video,
            //         $data['title'],
            //         $data['description']
            //     );
            // }

            $this->repository->update($id, $data);

            return redirect()->route('admin.video.index')->with('success', 'Video updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update video: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $video = $this->repository->find($id);
        $video->delete();

        return redirect()->route('admin.video.index')->with('success', 'Deleted successfully.');
    }

    public function googleCallback(Request $request)
    {
        $this->repository->handleGoogleCallback($request->get('code'));
        return redirect()->route('admin.video.index')->with('success', 'Google Authentication Successful');
    }

    public function fetchChannelVideos()
    {
        $data = $this->repository->getChannelVideos('UCWlbdVKAcA9kvjp2ag7Rt2w');
        if ($data['path'] == 'invalid_grant') {
            return redirect($data['authUrl']);
        }
        return redirect()->back()->with('success', 'Videos fetched successfully.');
    }
}
