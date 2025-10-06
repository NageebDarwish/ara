<?php

namespace App\Repositories\Admin;

use App\Models\Video;
use GuzzleHttp\Client;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use Exception;
use Google\Service\YouTube;
use Google_Service_YouTube;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class VideoRepository
{
    private $youtube;
    private $model;
    private $path;

    public function __construct(Video $model)
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
                // dd('hi');
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
        return $this->youtube = new YouTube($client);
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


    public function getChannelVideos($channelId)
    {
        if (!$this->youtube) {
            $authUrls = $this->handleYouTubeClient();
                if ($this->path == 'invalid_grant') {
                    $authUrls['path'] = 'invalid_grant';
                    return $authUrls;
                }
            }
        try {

            $channelResponse = $this->youtube->channels->listChannels('contentDetails', ['id' => $channelId]);
            if (empty($channelResponse['items'])) {
                throw new Exception('Channel not found.');
            }

            $uploadsPlaylistId = $channelResponse['items'][0]['contentDetails']['relatedPlaylists']['uploads'];
            if (!$uploadsPlaylistId) {
                throw new Exception('Uploads playlist not found for this channel.');
            }

            $videos = [];
            $pageToken = null;

            do {
                $playlistResponse = $this->youtube->playlistItems->listPlaylistItems(
                    'snippet',
                    [
                        'playlistId' => $uploadsPlaylistId,
                        'maxResults' => 50,
                        'pageToken' => $pageToken,
                    ]
                );

                $videoIds = [];
                foreach ($playlistResponse['items'] as $item) {
                    $videoIds[] = $item['snippet']['resourceId']['videoId'];
                }

                if (!empty($videoIds)) {
                    $videoDetailsResponse = $this->youtube->videos->listVideos(
                        'snippet,status,contentDetails',
                        ['id' => implode(',', $videoIds)]
                    );
                    // dd($videoDetailsResponse['items']);
                    foreach ($videoDetailsResponse['items'] as $video) {

                        $videos[] = [
                            'videoId' => $video['id'],
                            'title' => $video['snippet']['title'],
                            // 'description' => $video['snippet']['description'],
                            'thumbnail' => $video['snippet']['thumbnails']['default']['url'] ?? null,
                            'publishedAt' => $video['snippet']['publishedAt'],
                            'privacyStatus' => $video['status']['privacyStatus'],
                            'publishAt'     => $video['status']['publishAt'],
                            'duration' => $this->convertYouTubeDuration($video['contentDetails']['duration']),
                            'duration_seconds' => $this->convertYouTubeDurationToSeconds($video['contentDetails']['duration']),
                        ];
                    }
                }

                $pageToken = $playlistResponse['nextPageToken'] ?? null;
            } while ($pageToken);

            $this->create($videos);
            $result = [
                'videos' => $this->model::all(),
                'path'   => $this->path
            ];
            return $result;
        } catch (\Exception $e) {
            logger()->error('Error fetching videos: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    public function getVideosForDataTable($plan = null)
    {
        $query = $this->model->with(['level', 'topic', 'guide'])
            ->select(['id', 'title', 'publishedAt', 'scheduleDateTime', 'plan', 'level_id', 'topic_id', 'guide_id', 'video', 'duration'])
            ->orderBy('created_at', 'desc');

        if ($plan) {
            $query->where('plan', $plan);
        }

        return $query;
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $videos)
    {
        foreach ($videos as $video) {
            // Skip if private and no publish date
            // if ($video['privacyStatus'] === 'private' && empty($video['publishAt'])) {
            //     continue;
            // }

            $this->model::updateOrCreate(
                // Match condition (unique identifier)
                ['video' => $video['videoId']],

                // Data to update or create
                [
                    'title'            => $video['title'],
                    // 'description'      => $video['description'],
                    'video'            => $video['videoId'],
                    'publishedAt'      => $video['publishAt'] ?: $video['publishedAt'],
                    'status'           => $video['privacyStatus'],
                    'scheduleDateTime' => $video['publishAt'],
                    'duration'         => $video['duration'],
                    'duration_seconds' => $video['duration_seconds'],
                ]
            );
        }

        return true;
    }

    public function update($id, array $data)
    {
        // dd($data);
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
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

}
