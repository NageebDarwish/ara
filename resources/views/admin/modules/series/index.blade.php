@extends('admin.layout.layout')

@section('content')
    <div class="container mt-5">
        <div class="card">
           <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Series List</h3>
                <a href="{{ route('admin.video.fetchSeries') }}" class="btn btn-sm btn-primary ">Refresh</a>
            </div>

            <div class="card-body table-responsive">
                <table id="dataTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Level</th>
                            <th>Guide</th>
                            <!--<th>Description</th>-->
                            <th>Topic</th>
                            <!-- <th>Status</th> -->
                            <th>Plan</th>
                            <th>Publish Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $key => $s)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $s->title }}</td>
                                <td>{{ $s->level?->name ?? 'N/A' }}</td>
                                <td>{{ $s->guide?->name ?? 'N/A' }}</td>
                                <!--<td>{{ $s->description }}</td>-->
                                <td>{{ $s->topic->name ?? 'N/A' }}</td>
                                <!-- <td>
                                                            <span class="{{ $s->status == 'public' ? 'badge badge-success' : 'badge badge-secondary' }}">
                                                                {{ $s->status ?? 'N/A' }}
                                                            </span>
                                                        </td> -->
                                <td>
                                    <span
                                        class="{{ empty($s->plan)
                                            ? 'badge badge-secondary'
                                            : ($s->plan === 'free'
                                                ? 'badge badge-success'
                                                : ($s->plan === 'premium'
                                                    ? 'badge badge-warning'
                                                    : 'badge badge-danger')) }}">
                                        {{ empty($s->plan) ? 'N/A' : ucfirst($s->plan) }}
                                    </span>
                                </td>

                                <td>{{ \Carbon\Carbon::parse($s->scheduleDateTime ?? $s->publishedAt)->format('Y-m-d H:i:s') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.series.edit', $s->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                     @if (auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.series.destroy', $s->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this series?')">Delete</button>
                                    </form>
                                    @endif
                                    <button class="btn btn-info btn-sm" data-toggle="modal"
                                        data-target="#videoModal{{ $s->id }}">View Videos</button>
                                </td>
                            </tr>

                            <!-- Modal for Videos -->
                            <div class="modal fade " id="videoModal{{ $s->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="videoModalLabel{{ $s->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content ">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="videoModalLabel{{ $s->id }}">
                                                {{ $s->title }} - Related Videos</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="list-group row" id="video-list-{{ $s->id }}">
                                                @forelse ($s->videos as $video)
                                                    <div class="col-md-12 mb-4">
                                                        <div class="card h-100" data-video-id="{{ $video->id }}">
                                                            <div class="card-body">
                                                                <h5 class="card-title">{{ $video->title }}</h5>
                                                                <p class="card-text text-muted">{{ $video->description }}
                                                                </p>
                                                                <span
                                                                    class="badge badge-{{ $video->plan === 'premium' ? 'warning' : 'success' }}">
                                                                    {{ ucfirst($video->plan) }}
                                                                </span>
                                                                <div class="mt-3">
                                                                    <iframe class="w-100" height="200"
                                                                        src="https://www.youtube.com/embed/{{ $video->video }}"
                                                                        frameborder="0" allowfullscreen></iframe>
                                                                </div>
                                                                <!-- Plan Dropdown for Each Video -->
                                                                <div class="form-group mt-3">
                                                                    <label for="planDropdown{{ $video->id }}">Plan
                                                                        {{ $video->title }}:</label>
                                                                    <select class="form-control videoPlanDropdown"
                                                                        data-video-id="{{ $video->id }}"
                                                                        id="planDropdown{{ $video->id }}">
                                                                        <option value="">Select Plan</option>
                                                                        <option value="new"
                                                                            {{ $video->plan == 'new' ? 'selected' : '' }}>
                                                                            New</option>
                                                                        <option value="free"
                                                                            {{ $video->plan == 'free' ? 'selected' : '' }}>
                                                                            Free</option>
                                                                        <option value="premium"
                                                                            {{ $video->plan == 'premium' ? 'selected' : '' }}>
                                                                            Premium</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="col-12">
                                                        <div class="alert alert-info text-center" role="alert">
                                                            No videos found for this series.
                                                        </div>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No series found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true
            });
        });
    </script>
    <script>
        $(document).on('change', '.videoPlanDropdown', function() {
            var plan = $(this).val();
            var videoId = $(this).data('video-id');
            var dropdown = $(this); // Reference to the dropdown for later use

            // Make AJAX request to update the plan for the video
            $.ajax({
                url: '{{ route('admin.series.updatePlan') }}', // Route for updating the plan
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    video_id: videoId,
                    plan: plan,
                },
                success: function(response) {
                    if (response.success) {
                        // Update the badge dynamically
                        var badgeClass = plan === 'premium' ? 'badge-warning' : 'badge-success';
                        var planText = plan === 'premium' ? 'Premium' : 'Free';

                        dropdown
                            .closest('.card')
                            .find('.badge')
                            .removeClass('badge-warning badge-success')
                            .addClass(badgeClass)
                            .text(planText);

                        alert('Plan updated successfully');
                    } else {
                        alert('Failed to update plan');
                    }
                },
                error: function() {
                    alert('Something went wrong while updating the plan!');
                },
            });
        });

        // Update modal content dynamically when it is opened
        $(document).on('show.bs.modal', '.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var seriesId = button.data('series-id'); // Extract series ID
            var modal = $(this); // Modal element

            // Fetch the latest plan for each video in the series
            $.ajax({
                url: '{{ route('admin.series.getVideosWithPlan') }}', // Route to fetch video data
                method: 'GET',
                data: {
                    series_id: seriesId,
                },
                success: function(response) {
                    if (response.success) {
                        // Populate videos dynamically with the latest plan
                        var videoList = response.data.videos;
                        var videoContainer = modal.find('.list-group'); // Container for video cards
                        videoContainer.empty(); // Clear existing content

                        videoList.forEach(function(video) {
                            var videoCard = `
                        <div class="col-md-12 mb-4">
                            <div class="card h-100" data-video-id="${video.id}">
                                <div class="card-body">
                                    <h5 class="card-title">${video.title}</h5>
                                    <p class="card-text text-muted">${video.description}</p>
                                    <span class="badge badge-${video.plan === 'premium' ? 'warning' : 'success'}">
                                        ${video.plan === 'premium' ? 'Premium' : 'Free'}
                                    </span>
                                    <div class="mt-3">
                                        <iframe class="w-100" height="200" src="https://www.youtube.com/embed/${video.video}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="planDropdown${video.id}">Plan for ${video.title}:</label>
                                        <select class="form-control videoPlanDropdown" data-video-id="${video.id}" id="planDropdown${video.id}">
                                            <option value="">Select Plan</option>
                                                                       <option value="new"
                                                                            ${ video.plan == 'new' ? 'selected' : '' }>
                                                                            New</option>
                                            <option value="free" ${video.plan === 'free' ? 'selected' : ''}>Free</option>
                                            <option value="premium" ${video.plan === 'premium' ? 'selected' : ''}>Premium</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                            videoContainer.append(videoCard);
                        });
                    } else {
                        alert('Failed to load video data');
                    }
                },
                error: function() {
                    alert('Something went wrong while fetching video data!');
                },
            });
        });
    </script>

@endsection
