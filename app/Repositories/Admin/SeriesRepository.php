<?php

namespace App\Repositories\Admin;

use App\Models\Series;
use App\Models\SeriesVideo;
use App\Models\Video;
use GuzzleHttp\Client;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use Exception;
use DB;
use Google_Service_YouTube;
use Google\Service\YouTube;
use Carbon\Carbon;
use App\Helpers\UploadFiles;


class SeriesRepository
{
    private $youtube;
    private $model;
    private $path;

    public function __construct(Series $model)
    {
        $this->model = $model;
    }

    public function handleYouTubeClient()
    {
        $client = new GoogleClient();
        $client->setApplicationName('My Laravel YouTube Integration');
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube.readonly',
            'https://www.googleapis.com/auth/youtube',
            'https://www.googleapis.com/auth/youtube.force-ssl'
        ]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');

        if (Storage::exists('refresh_token.json')) {
            $accessToken = json_decode(Storage::get('refresh_token.json'), true);
            if(isset($accessToken['error']) == 'invalid_grant')
            {
                $this->path = 'invalid_grant';
                $authUrl = $client->createAuthUrl();
                return [
                    'success' => false,
                    'authUrl' => $authUrl,
                ];
            }
            $client->setAccessToken($accessToken);

            if ($client->isAccessTokenExpired()) {
                $refreshToken = $accessToken['refresh_token'] ?? null;
                $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
                Storage::put('refresh_token.json', json_encode($newToken));
                $this->path = null;
            }
        } else {
            $authUrl = $client->createAuthUrl();
            return [
                'success' => false,
                'authUrl' => $authUrl,
            ];
        }

        $this->youtube = new YouTube($client);
    }

    public function handleGoogleCallback($code)
    {
        $client = new \Google\Client();
        $client->setApplicationName('My Laravel YouTube Integration');
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube.readonly',
            'https://www.googleapis.com/auth/youtube',
            'https://www.googleapis.com/auth/youtube.force-ssl',
        ]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');

        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode($code);
            if (isset($accessToken['error'])) {
                throw new \Exception("Error fetching access token: " . $accessToken['error']);
            }

            if (isset($accessToken['refresh_token'])) {
                Storage::put('refresh_token.json', json_encode($accessToken));
            } else {
                if (Storage::exists('refresh_token.json')) {
                    $existingToken = json_decode(Storage::get('refresh_token.json'), true);
                    $accessToken['refresh_token'] = $existingToken['refresh_token'];
                }
                Storage::put('refresh_token.json', json_encode($accessToken));
            }

            $client->setAccessToken($accessToken);
            $this->youtube = new \Google\Service\YouTube($client);

            return redirect()->route('admin.video.index')->with('success', 'YouTube authorization successful!');
        } catch (\Exception $e) {
            return redirect()->route('admin.videos.index')->with('error', 'Failed to handle Google callback: ' . $e->getMessage());
        }
    }

   public function getPlaylistsAndItems($channelId)
{
    if (!$this->youtube) {
        $authUrls = $this->handleYouTubeClient();
        if ($this->path == 'invalid_grant') {
            $authUrls['path'] = 'invalid_grant';
            return $authUrls;
        }
    }

    try {
        $playlists = [];
        $pageToken = null;

        // Step 1: Fetch all playlists for the given channel
        do {
            $playlistResponse = $this->youtube->playlists->listPlaylists(
                'snippet,contentDetails,status',
                [
                    'channelId' => $channelId,
                    'maxResults' => 50,
                    'pageToken' => $pageToken,
                ]
            );

            foreach ($playlistResponse['items'] as $playlist) {
                $playlists[] = [
                    'playlistId' => $playlist['id'],
                    'title' => $playlist['snippet']['title'],
                    'description' => $playlist['snippet']['description'],
                    'videoCount' => $playlist['contentDetails']['itemCount'],
                    'publishedAt' => $playlist['snippet']['publishedAt'],
                    'status' => $playlist['status']['privacyStatus'],
                    'publishAt' => $playlist['status']['publishAt'],
                    'thumbnail' => $playlist['snippet']['thumbnails']['default']['url'] ?? null,
                ];
            }

            $pageToken = $playlistResponse['nextPageToken'] ?? null;
        } while ($pageToken);

        // Step 2: Fetch all items (videos) for each playlist
        $playlistItems = [];
        $allVideoIds = []; // To collect all video IDs for batch request

        foreach ($playlists as $playlist) {
            $playlistId = $playlist['playlistId'];
            $videos = [];
            $videoPageToken = null;

            do {
                $playlistItemsResponse = $this->youtube->playlistItems->listPlaylistItems(
                    'snippet,contentDetails,status',
                    [
                        'playlistId' => $playlistId,
                        'maxResults' => 50,
                        'pageToken' => $videoPageToken,
                    ]
                );

                foreach ($playlistItemsResponse['items'] as $item) {
                    $videoId = $item['contentDetails']['videoId'];
                    $allVideoIds[] = $videoId;

                    $videos[] = [
                        'videoId' => $videoId,
                        'title' => $item['snippet']['title'],
                        'description' => $item['snippet']['description'],
                        'thumbnail' => $item['snippet']['thumbnails']['default']['url'] ?? null,
                        'publishedAt' => $item['snippet']['publishedAt'],
                        'status' => $item['status']['privacyStatus'],
                        'publishAt' => $item['status']['publishAt'],
                        // Duration will be added later
                    ];
                }

                $videoPageToken = $playlistItemsResponse['nextPageToken'] ?? null;
            } while ($videoPageToken);

            $playlistItems[] = [
                'playlist' => $playlist,
                'videos' => $videos,
            ];
        }

        // Step 3: Get video durations in batch (50 at a time)
        $videoDurations = [];
        $chunkedVideoIds = array_chunk($allVideoIds, 50); // YouTube allows max 50 IDs per request

        foreach ($chunkedVideoIds as $videoIdsChunk) {
            $videosResponse = $this->youtube->videos->listVideos(
                'contentDetails',
                ['id' => implode(',', $videoIdsChunk)]
            );

            foreach ($videosResponse['items'] as $video) {

                $videoDurations[$video['id']] = [
                    'duration' => $this->convertYouTubeDuration($video['contentDetails']['duration']),
                    'duration_seconds' => $this->convertYouTubeDurationToSeconds($video['contentDetails']['duration'])
                ];
            }
        }

        // Step 4: Merge duration information back into playlist items
        foreach ($playlistItems as &$playlistItem) {
            foreach ($playlistItem['videos'] as &$video) {

                if (isset($videoDurations[$video['videoId']])) {

                    $video['duration'] = $videoDurations[$video['videoId']]['duration'];
                    $video['duration_seconds'] = $videoDurations[$video['videoId']]['duration_seconds'];
                }
            }
        }

        // Save data to the database
        $this->create($playlistItems);
        $result = [
            'series' => $this->model::all(),
            'path'   => $this->path
        ];
        return $result;
    } catch (\Exception $e) {
        logger()->error('Error fetching playlists and items: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()]);
    }
}

   public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    public function getSeriesForDataTable()
    {
        return $this->model->with(['level'])
            ->select(['id', 'title', 'level_id', 'publishedAt', 'scheduleDateTime'])
            ->orderBy('created_at', 'desc');
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

public function create($playlistItems)
{
    try {
        foreach ($playlistItems as $playlistId => $data) {
            // Update or create the playlist
            $playlist = $this->model::updateOrCreate(
                ['playlist_id' => $data['playlist']['playlistId']],
                [
                    'title'       => $data['playlist']['title'],
                    // 'description' => $data['playlist']['description'] ,
                    'status'      => $data['playlist']['status'],
                    // 'thumbnail'  => $data['playlist']['thumbnail'],
                    'publishedAt' => $data['playlist']['publishedAt'],
                    'publishAt'   => $data['playlist']['publishAt'],
                ]
            );

            // Update or create videos in the playlist
            foreach ($data['videos'] as $videoData) {
                // First, create or update the video in the videos table
                $video = Video::updateOrCreate(
                    ['video' => $videoData['videoId']], // Find by YouTube video ID
                    [
                        'title'            => $videoData['title'],
                        'description'      => $videoData['description'],
                        'level_id'         => $playlist->level_id,
                        'guide_id'         => null, // Series don't have guide_id
                        'topic_id'         => null, // Can be set manually later if needed
                        'country_id'       => $playlist->country_id,
                        'plan'             => 'new', // Default plan for new videos
                        'publishedAt'      => $videoData['publishedAt'],
                        'scheduleDateTime' => $videoData['publishAt'],
                        'status'           => $videoData['publishAt'] ? 'schedule' : $videoData['status'],
                        'duration'         => $videoData['duration'],
                        'duration_seconds' => $videoData['duration_seconds'],
                        'is_hide'          => false,
                    ]
                );

                // Then, create or update the series_video relationship
                SeriesVideo::updateOrCreate(
                    [
                        'video_id'    => $video->id,
                        'series_id'   => $playlist->id,
                    ],
                    [
                        'plan'        => 'new', // Admin can change this to free/premium
                        'playlist_id' => $data['playlist']['playlistId'],
                        'is_hide'     => false,
                    ]
                );
            }
        }

        return true;
    } catch (\Exception $e) {
        DB::rollBack();
        throw new \Exception($e->getMessage());
    }
}



    public function update($id, array $data)
    {
        $model = $this->model->findOrFail($id);
        if(isset($data['thumbnail']))
        {
            if($model->thumbnail)
            {
                UploadFiles::delete($model->thumbnail,'series');
            }
            $data['thumbnail']=UploadFiles::upload($data['thumbnail'],'series');
        }
          if(isset($data['vertical_thumbnail']))
        {
            if($model->vertical_thumbnail)
            {
                UploadFiles::delete($model->vertical_thumbnail,'series');
            }
            $data['vertical_thumbnail']=UploadFiles::upload($data['vertical_thumbnail'],'series');
        }

        $model->update($data);

        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        if($model->thumbnail)
            {
                UploadFiles::delete($model->thumbnail,'series');
            }
        if($model->vertical_thumbnail)
            {
                UploadFiles::delete($model->vertical_thumbnail,'series');
            }
        foreach($model->videos as $seriesVideo)
        {
            // Access the YouTube video ID through the video relationship
            if ($seriesVideo->video) {
                $this->deleteVideoFromYouTube(youtubeVideoId: $seriesVideo->video->video);
            }
        }
        return $model->delete();
    }


    public function convertYouTubeDuration($duration)
    {
          $interval = new \DateInterval($duration);
            return sprintf(
                '%02d:%02d:%02d',
                $interval->h,
                $interval->i,
                $interval->s
            );
    }

    public function convertYouTubeDurationToSeconds($duration) {
            $interval = new \DateInterval($duration);
            return ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
    }

    public function assignVideoToSeries($seriesId, $videoId, $plan = 'new')
    {
        try {
            $series = $this->model->findOrFail($seriesId);
            $video = Video::findOrFail($videoId);

            // Check if video is already assigned to this series
            $existingAssignment = SeriesVideo::where('series_id', $seriesId)
                ->where('video_id', $videoId)
                ->first();

            if ($existingAssignment) {
                // Update the plan if it already exists
                $existingAssignment->plan = $plan;
                $existingAssignment->save();
                return $existingAssignment;
            }

            // Create new assignment
            $seriesVideo = SeriesVideo::create([
                'series_id' => $seriesId,
                'video_id' => $videoId,
                'plan' => $plan,
                'playlist_id' => $series->playlist_id,
                'is_hide' => false,
            ]);

            return $seriesVideo;
        } catch (\Exception $e) {
            throw new \Exception('Failed to assign video to series: ' . $e->getMessage());
        }
    }

    public function removeVideoFromSeries($seriesId, $videoId)
    {
        try {
            $seriesVideo = SeriesVideo::where('series_id', $seriesId)
                ->where('video_id', $videoId)
                ->firstOrFail();

            return $seriesVideo->delete();
        } catch (\Exception $e) {
            throw new \Exception('Failed to remove video from series: ' . $e->getMessage());
        }
    }
}
