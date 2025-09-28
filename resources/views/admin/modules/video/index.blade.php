@extends('admin.layout.layout')
@section('content')
<div class="mt-5">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title ">Video List</h3>
                <a href="{{ route('admin.video.fetchVideos') }}" class="btn btn-sm btn-primary ">Refresh</a>
            </div>

        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <!-- Nav Tabs -->
            <ul class="nav nav-tabs" id="videoTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="new"
                        aria-selected="true">New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="free-tab" data-toggle="tab" href="#free" role="tab" aria-controls="free"
                        aria-selected="false">Free</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="premium-tab" data-toggle="tab" href="#premium" role="tab"
                        aria-controls="premium" aria-selected="false">Premium</a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content mt-3" id="videoTabContent">
                <!-- New Tab -->
                <div class="tab-pane fade show active" id="new" role="tabpanel" aria-labelledby="new-tab">
                    <div class="table-responsive">
                        @if((count($data->where('plan', 'new')) > 0))
                        <table id="videoTable-new" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Published Date</th>
                                    <th>Actions</th>
                                    <th>Level</th>
                                    <th>Topic</th>
                                    <th>Guide</th>
                                    <th>Plan</th>
                                    <th>Video</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data->where('plan', 'new') as $video)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Str::limit($video->title, 50) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($video->scheduleDateTime ?? $video->publishedAt)->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.video.edit', $video->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                             @if (auth()->user()->role === 'admin')
                                        <form action="{{ route('admin.video.destroy', $video->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this video?')">Delete</button>
                                        </form>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($video->level?->name ?? 'N/A') }}</td>
                                    <td>{{ $video->topic?->name ?? 'N/A' }}</td>
                                    <td>{{ $video->guide?->name ?? 'N/A' }}</td>
                                    <td><span class="badge badge-info">New</span></td>

                                    <td>
                                        <button class="btn btn-sm btn-primary open-video-modal" data-toggle="modal"
                                            data-video-url="{{ $video->video_url }}"
                                            data-video-title="{{ $video->title }}"
                                            data-target="#videoModal-{{ $video->id }}">
                                            Watch Video
                                        </button>
                                    </td>

                                     <td>{{ $video->duration ?? 'N/A' }}</td>
                                </tr>
                                <!-- Modal for this video -->
                                <div class="modal fade" id="videoModal-{{ $video->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="videoModalLabel-{{ $video->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="videoModalLabel-{{ $video->id }}">
                                                    {{ $video->title }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mt-3">
                                                    <iframe class="w-100" height="200"
                                                        src="https://www.youtube.com/embed/{{ $video->video }}"
                                                        frameborder="0" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No new videos found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>

                <!-- Free Tab -->
                <div class="tab-pane fade" id="free" role="tabpanel" aria-labelledby="free-tab">
                    <div class="table-responsive">
                        @if((count($data->where('plan', 'free')) > 0))
                        <table id="videoTable-free" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Published Date</th>
                                    <th>Actions</th>
                                    <th>Level</th>
                                    <th>Topic</th>
                                    <th>Guide</th>
                                    <th>Plan</th>
                                    <th>Video</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data->where('plan', 'free') as $video)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Str::limit($video->title, 50) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($video->scheduleDateTime ?? $video->publishedAt)->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.video.edit', $video->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.video.destroy', $video->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this video?')">Delete</button>
                                        </form>
                                    </td>
                                    <td>{{ ucfirst($video->level?->name ?? 'N/A') }}</td>
                                    <td>{{ $video->topic?->name ?? 'N/A' }}</td>
                                    <td>{{ $video->guide?->name ?? 'N/A' }}</td>
                                    <td><span class="badge badge-warning">Free</span></td>

                                    <td>
                                        <button class="btn btn-sm btn-primary open-video-modal" data-toggle="modal"
                                            data-video-url="{{ $video->video_url }}"
                                            data-video-title="{{ $video->title }}"
                                            data-target="#videoModal-{{ $video->id }}">
                                            Watch Video
                                        </button>
                                    </td>

                                </tr>
                                <!-- Modal for this video -->
                                <div class="modal fade" id="videoModal-{{ $video->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="videoModalLabel-{{ $video->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="videoModalLabel-{{ $video->id }}">
                                                    {{ $video->title }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mt-3">
                                                    <iframe class="w-100" height="200"
                                                        src="https://www.youtube.com/embed/{{ $video->video }}"
                                                        frameborder="0" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No free videos found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>

                <!-- Premium Tab -->
                <div class="tab-pane fade" id="premium" role="tabpanel" aria-labelledby="premium-tab">
                    <div class="table-responsive">
                        @if((count($data->where('plan', 'premium')) > 0))
                        <table id="videoTable-premium" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Published Date</th>
                                    <th>Actions</th>
                                    <th>Level</th>
                                    <th>Topic</th>
                                    <th>Guide</th>
                                    <th>Plan</th>
                                    <th>Video</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data->where('plan', 'premium') as $video)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Str::limit($video->title, 50) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($video->scheduleDateTime ?? $video->publishedAt)->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.video.edit', $video->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.video.destroy', $video->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this video?')">Delete</button>
                                        </form>
                                    </td>
                                    <td>{{ ucfirst($video->level?->name ?? 'N/A') }}</td>
                                    <td>{{ $video->topic?->name ?? 'N/A' }}</td>
                                    <td>{{ $video->guide?->name ?? 'N/A' }}</td>
                                    <td><span class="badge badge-success">Premium</span></td>

                                    <td>
                                        <button class="btn btn-sm btn-primary open-video-modal" data-toggle="modal"
                                            data-video-url="{{ $video->video_url }}"
                                            data-video-title="{{ $video->title }}"
                                            data-target="#videoModal-{{ $video->id }}">
                                            Watch Video
                                        </button>
                                    </td>

                                </tr>
                                <!-- Modal for this video -->
                                <div class="modal fade" id="videoModal-{{ $video->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="videoModalLabel-{{ $video->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="videoModalLabel-{{ $video->id }}">
                                                    {{ $video->title }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mt-3">
                                                    <iframe class="w-100" height="200"
                                                        src="https://www.youtube.com/embed/{{ $video->video }}"
                                                        frameborder="0" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No premium videos found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#videoTable-new').DataTable();
    $('#videoTable-free').DataTable();
    $('#videoTable-premium').DataTable();
});
</script>
@endsection
