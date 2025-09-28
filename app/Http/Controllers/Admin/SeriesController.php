<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SeriesRequest;
use App\Repositories\Admin\SeriesRepository;
use Illuminate\Http\Request;
use App\Models\{Topic, Guide, Level, SeriesVideo};
use App\Models\Country;
use Exception;

class SeriesController extends Controller
{
    protected $repository;

    public function __construct(SeriesRepository $repository)
    {
        $this->repository = $repository;
    }

  public function index()
    {

        $data = $this->repository->all();
        return view('admin.modules.series.index', compact('data'));
    }

    public function create()
    {
        if (!$this->repository->googleClient->getAccessToken()) {
            $authUrl = $this->repository->getAuthUrl();
            return redirect()->away($authUrl);
        }

        $countries = Country::all();
        $levels = Level::all();
        return view('admin.modules.series.create',compact('countries', 'levels'));
    }

   public function store(SeriesRequest $request)
    {
        // dd($request->all());
        // Validate the request data
        $seriesData = $request->only(['level_id', 'country_id', 'title', 'description']);
        $videoTitles = $request->input('video_title');
        $videoDescriptions = $request->input('video_description');
        $videos = $request->file('videos');

        $videosData = [];

        // try {



            // Loop through each video entry
            foreach ($videos as $index => $videoFile) {

                $videoId = $this->repository->uploadVideoToYouTube(
                    $videoFile,
                    $videoTitles[$index],
                    $videoDescriptions[$index],
                );


                $videosData[] = [
                    'video' => $videoId,
                    'title' => $videoTitles[$index],
                    'description' => $videoDescriptions[$index],
                ];
                sleep(2);
            }

            // Create the series and associate the videos in one step
            $this->repository->create($seriesData, $videosData);

            return redirect()->route('admin.series.index')->with('success', 'Created successfully.');
        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        // }
    }

    public function edit($id)
    {
        $countries = Country::all();
        $levels = Level::all();
        $series = $this->repository->find($id);

        return view('admin.modules.series.edit', compact('series','countries','levels'));
    }

    public function update(Request $request, $id)
    {
        // Retrieve the series from the database
        $series = $this->repository->find($id);

        // Get all request data
        $data = $request->all();

        try {
            // Update series record in the local database (without touching videos)
            $this->repository->update($id, $data);

            // Check if any video titles and descriptions were submitted for update
            // if ($request->has('video_title') && $request->has('video_description')) {
            //     $videoTitles = $request->input('video_title');
            //     $videoDescriptions = $request->input('video_description');

            //     // Assuming $series->videos gives you the current videos associated with the series
            //     foreach ($series->videos as $index => $video) {
            //         // Update video on YouTube if it exists
            //         if ($video->video) {
            //             $this->repository->updateVideoOnYouTube(
            //                 $video->video,
            //                 $videoTitles[$index], // Update title from the form
            //                 $videoDescriptions[$index] // Update description from the form
            //             );
            //         }

            //         // Update the video record in the database with the new title and description
            //         $video->update([
            //             'title' => $videoTitles[$index],
            //             'description' => $videoDescriptions[$index],
            //         ]);
            //     }
            // }

            return redirect()->route('admin.series.index')->with('success', 'Series updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update series: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.series.index')->with('success', 'Deleted successfully.');
    }

    public function updatePlan(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:series_videos,id',
            'plan' => 'required|in:free,premium',
        ]);

        $series = SeriesVideo::find($request->video_id);
        $series->plan = $request->plan;
        $series->save();

        return response()->json(['success' => true]);
    }

    public function getVideosWithPlan(Request $request)
    {
        $seriesId = $request->get('series_id');
        $videos = SeriesVideo::where('series_id', $seriesId)->get();

        return response()->json([
            'success' => true,
            'data' => ['videos' => $videos]
        ]);
    }


    public function fetchChannelSeries()
    {
        $data = $this->repository->getPlaylistsAndItems('UCWlbdVKAcA9kvjp2ag7Rt2w');
        if($data['path'] == 'invalid_grant')
        {
            return redirect($data['authUrl']);
        }

        return redirect()->back()->with('success', 'Series fetched successfully.');
    }


}
