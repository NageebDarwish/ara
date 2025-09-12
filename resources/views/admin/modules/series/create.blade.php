@extends('admin.layout.layout')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create New Series</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.series.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Series Fields -->
                    <!-- Level Selection -->
                    <div class="form-group">
                        <label for="level_id">Level</label>
                        <select name="level_id" id="level_id" class="form-control" required>
                            <option value="">Select Level</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('level_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Topic Selection -->
                    <div class="form-group">
                        <label for="topic_id">Topic</label>
                        <select name="topic_id" id="topic_id" class="form-control" required>
                            <option value="">Select Topic</option>
                            @foreach ($topics as $topic)
                                <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                                    {{ $topic->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('topic_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Guide Selection -->
                    <div class="form-group">
                        <label for="guide_id">Guide</label>
                        <select name="guide_id" id="guide_id" class="form-control" required>
                            <option value="">Select Guide</option>
                            @foreach ($guides as $guide)
                                <option value="{{ $guide->id }}" {{ old('guide_id') == $guide->id ? 'selected' : '' }}>
                                    {{ $guide->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('guide_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title">Series Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Series Description</label>
                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Video File Fields -->
                    <h5>Videos</h5>
                    <div id="video-fields">
                        <div class="video-entry mb-3">
                            <label for="video_title">Video Title</label>
                            <input type="text" name="video_title[]" class="form-control">
                            @error('video_title.*')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <label for="video_description">Video Description</label>
                            <textarea name="video_description[]" class="form-control"></textarea>
                            @error('video_description.*')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <div class="form-group mt-3">
                                <label for="plan[]">Plan</label>
                                <select name="plan[]" class="form-control">
                                    <option value="free">Free</option>
                                    <option value="premium">Premium</option>
                                </select>
                                @error('plan.*')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <label for="video_file">Upload Video File</label>
                            <input type="file" name="videos[]" class="form-control-file border" accept="video/*">
                            @error('videos.*')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="button" id="add-video" class="btn btn-secondary">Add Another Video</button>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Create Series</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add new video fields dynamically
        document.getElementById('add-video').addEventListener('click', function() {
            const videoFields = document.getElementById('video-fields');
            const videoEntry = `
                <div class="video-entry mb-3">
                    <label for="video_title">Video Title</label>
                    <input type="text" name="video_title[]" class="form-control">

                    <label for="video_description">Video Description</label>
                    <textarea name="video_description[]" class="form-control"></textarea>

                    <div class="form-group mt-3">
                        <label for="plan">Plan</label>
                        <select name="plan[]" class="form-control">
                                <option value="free">Free</option>
                                <option value="premium">Premium</option>
                        </select>
                    </div>

                    <label for="video_file">Upload Video File</label>
                    <input type="file" name="videos[]" class="form-control-file border" accept="video/*">
                    <button type="button" class="btn btn-danger remove-video">Remove</button>
                </div>`;
            videoFields.insertAdjacentHTML('beforeend', videoEntry);
        });

        // Remove video fields dynamically
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-video')) {
                e.target.closest('.video-entry').remove();
            }
        });
    </script>
@endsection
