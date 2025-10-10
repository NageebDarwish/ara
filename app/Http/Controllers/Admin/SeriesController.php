<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SeriesRequest;
use App\Repositories\Admin\SeriesRepository;
use Illuminate\Http\Request;
use App\Models\{Topic, Guide, Level, SeriesVideo, Video};
use App\Models\Country;
use Exception;
use Illuminate\Support\Facades\Auth;

class SeriesController extends Controller
{
    protected $repository;

    public function __construct(SeriesRepository $repository)
    {
        $this->repository = $repository;
    }

  public function index()
    {
        return view('admin.modules.series.index');
    }

    public function getSeriesData(Request $request)
    {
        $series = $this->repository->getSeriesForDataTable();

        return \Yajra\DataTables\Facades\DataTables::of($series)
            ->addIndexColumn()
            ->addColumn('level', function ($s) {
                return $s->level?->name ?? 'N/A';
            })
            ->addColumn('publishDate', function ($s) {
                return \Carbon\Carbon::parse($s->scheduleDateTime ?? $s->publishedAt)->format('Y-m-d H:i:s');
            })
            ->addColumn('actions', function ($s) {
                $actions = '<div class="d-flex align-items-center gap-2" style="gap: 0.5rem;">';
                $actions .= '<a href="' . route('admin.series.edit', $s->id) . '" class="btn btn-warning btn-sm" title="Edit Series"><i class="fa fa-edit"></i></a>';
                if (Auth::check() && Auth::user()->role === 'admin') {
                    $actions .= '<form action="' . route('admin.series.destroy', $s->id) . '" method="POST" style="display:inline-block;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm delete-btn" title="Delete Series"><i class="fa fa-trash"></i></button>
                    </form>';
                }
                $actions .= '<button class="btn btn-info btn-sm view-videos-btn" data-series-id="' . $s->id . '" data-series-title="' . htmlspecialchars($s->title) . '" title="View Videos"><i class="fa fa-play-circle"></i></button>';
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        $countries = Country::all();
        $levels = Level::all();
        return view('admin.modules.series.create',compact('countries', 'levels'));
    }

   public function store(SeriesRequest $request)
    {
        $seriesData = $request->only(['level_id', 'country_id', 'title', 'description']);
        $videosData = [];

        try {
            $this->repository->create($seriesData, $videosData);
            return redirect()->route('admin.series.index')->with('success', 'Created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
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

    public function assignVideo(Request $request)
    {
        $request->validate([
            'series_id' => 'required|exists:series,id',
            'video_id' => 'required|exists:videos,id',
            'plan' => 'required|in:new,free,premium',
        ]);

        try {
            $this->repository->assignVideoToSeries(
                $request->series_id,
                $request->video_id,
                $request->plan
            );

            return response()->json(['success' => true, 'message' => 'Video assigned to series successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function removeVideo(Request $request)
    {
        $request->validate([
            'series_id' => 'required|exists:series,id',
            'video_id' => 'required|exists:videos,id',
        ]);

        try {
            $this->repository->removeVideoFromSeries(
                $request->series_id,
                $request->video_id
            );

            return response()->json(['success' => true, 'message' => 'Video removed from series successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function getAvailableVideos(Request $request)
    {
        $seriesId = $request->get('series_id');

        // Get all videos that are not already in this series
        $assignedVideoIds = SeriesVideo::where('series_id', $seriesId)->pluck('video_id');
        $availableVideos = Video::whereNotIn('id', $assignedVideoIds)
            ->with(['level', 'topic'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => ['videos' => $availableVideos]
        ]);
    }

}
