@extends('admin.layout.layout')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Series</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.series.update', $series->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Level Selection -->
                    <div class="form-group">
                        <label for="level_id">Level</label>
                        <select name="level_id" id="level_id" class="form-control">
                            <option value="">Select Level</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level->id }}" {{ $series->level_id == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Topic Selection -->
                    <div class="form-group">
                        <label for="topic_id">Topic</label>
                        <select name="topic_id" id="topic_id" class="form-control">
                            <option value="">Select Topic</option>
                            @foreach ($topics as $topic)
                                <option value="{{ $topic->id }}" {{ $series->topic_id == $topic->id ? 'selected' : '' }}>
                                    {{ $topic->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Guide Selection -->
                    <div class="form-group">
                        <label for="guide_id">Guide</label>
                        <select name="guide_id" id="guide_id" class="form-control">
                            <option value="">Select Guide</option>
                            @foreach ($guides as $guide)
                                <option value="{{ $guide->id }}" {{ $series->guide_id == $guide->id ? 'selected' : '' }}>
                                    {{ $guide->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="plan">Plan</label>
                        <select name="plan" class="form-control">
                            <option value="">Select Plan</option>
                            <option value="new" {{ $series->plan === 'new' ? 'selected' : '' }}>New</option>
                            <option value="free" {{ $series->plan === 'free' ? 'selected' : '' }}>Free</option>
                            <option value="premium" {{ $series->plan === 'premium' ? 'selected' : '' }}>Premium</option>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                                                                <label for="guide_id">Status</label>
                                                                <select name="status" id="guide_id" class="form-control" required>
                                                                    <option value="">Select Status</option>
                                                                    <option value="public" {{ $series->status == 'public' ? 'selected' : '' }}>
                                                                        Public
                                                                    </option>
                                                                    <option value="private" {{ $series->status == 'private' ? 'selected' : '' }}>
                                                                        Private
                                                                    </option>
                                                                    <option value="schedule" {{ $series->status == 'schedule' ? 'selected' : '' }}>
                                                                        Schedule
                                                                    </option>
                                                                </select>
                                                            </div> -->

                    <div class="form-group">
                        <label for="title">Series Title</label>
                        <input type="text" name="title" class="form-control"
                            value="{{ old('title', $series->title) }}">
                    </div>

                    <div class="form-group">
                        <label for="description">Series Description</label>
                        <textarea name="description" class="form-control">{{ old('description', $series->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail">Thumbnail</label>
                        @if ($series->thumbnail)
                            <div class="mb-2">
                                <img src="{{ $series->thumbnail }}" alt="Current thumbnail" class="img-thumbnail"
                                    style="max-height: 150px;">
                            </div>
                        @endif
                        <input type="file" name="thumbnail" class="form-control-file">
                        <small class="form-text text-muted">Leave blank to keep current thumbnail</small>
                    </div>

                    <div class="form-group">
                        <label for="vertical_thumbnail">Vertical Thumbnail</label>
                        @if ($series->vertical_thumbnail)
                            <div class="mb-2">
                                <img src="{{ $series->vertical_thumbnail }}" alt="Current vertical thumbnail"
                                    class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        @endif
                        <input type="file" name="vertical_thumbnail" class="form-control-file">
                        <small class="form-text text-muted">Leave blank to keep current vertical thumbnail</small>
                    </div>

                    <!-- Video File Fields -->
                    <!--<h5>Videos</h5>-->
                    <!--<div id="video-fields">-->
                    <!--    @foreach ($series->videos as $video)
    -->
                    <!--        <div class="video-entry mb-3">-->
                    <!--            <label for="video_title">Video Title</label>-->
                    <!--            <input type="text" name="video_title[]" class="form-control"-->
                    <!--                value="{{ $video->title }}">-->

                    <!--            <label for="video_description">Video Description</label>-->
                    <!--            <textarea name="video_description[]" class="form-control">{{ $video->description }}</textarea>-->


                    <!--            {{--  <button type="button" class="btn btn-danger remove-video">Remove</button>  --}}-->
                    <!--        </div>-->
                    <!--
    @endforeach-->
                    <!--</div>-->

                    {{--  <button type="button" id="add-video" class="btn btn-secondary">Add Another Video</button>  --}}

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Update Series</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add new video fields dynamically
        // document.getElementById('add-video').addEventListener('click', function() {
        //     const videoFields = document.getElementById('video-fields');
        //     const videoEntry = `
    //         <div class="video-entry mb-3">
    //             <label for="video_title">Video Title</label>
    //             <input type="text" name="video_title[]" class="form-control">

    //             <label for="video_description">Video Description</label>
    //             <textarea name="video_description[]" class="form-control"></textarea>

    //             <label for="video_file">Upload Video File</label>
    //             <input type="file" name="videos[]" class="form-control">
    //             <button type="button" class="btn btn-danger remove-video">Remove</button>
    //         </div>`;
        //     videoFields.insertAdjacentHTML('beforeend', videoEntry);
        // });

        // Remove video fields dynamically
        // document.addEventListener('click', function(e) {
        //     if (e.target && e.target.classList.contains('remove-video')) {
        //         e.target.closest('.video-entry').remove();
        //     }
        // });
    </script>
@endsection
