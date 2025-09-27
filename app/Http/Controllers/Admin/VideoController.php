<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VideoRequest;
use App\Repositories\Admin\VideoRepository;
use Illuminate\Http\Request;
use App\Models\{Topic, Guide, Level, Video};
use App\Models\Country;
use Exception;

class VideoController extends Controller
{
    protected $repository;

    public function __construct(VideoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = $this->repository->all();
        return view('admin.modules.video.index', compact('data'));
    }

    public function create()
    {
        if (!$this->repository->googleClient->getAccessToken()) {
            $authUrl = $this->repository->getAuthUrl();
            return redirect()->away($authUrl);
        }

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
            $videoId = $this->repository->uploadVideoToYouTube(
                $request->file('video'),
                $data['title'],
                $data['description'],
            );
            $data['video'] = $videoId;
            $this->repository->create($data);

            return redirect()->route('admin.video.index')->with('success', 'Video uploaded and created successfully.');
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->route('admin.video.index')->with('error', 'Failed to upload video: ' . $e->getMessage());
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
        $this->repository->delete($id);

        return redirect()->route('admin.video.index')->with('success', 'Deleted successfully.');
    }

    public function googleAuth()
    {
        return redirect($this->repository->getAuthUrl());
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