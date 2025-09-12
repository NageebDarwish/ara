@extends('admin.layout.layout')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Video</h3>
                <a href="{{ route('admin.video.index') }}" class="btn btn-secondary float-right">Back</a>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.video.update', $video->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Level Selection -->
                    <div class="form-group">
                        <label for="level_id">Level</label>
                        <select name="level_id" id="level_id" class="form-control">
                            <option value="">Select Level</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level->id }}" {{ $video->level_id == $level->id ? 'selected' : '' }}>
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
                                <option value="{{ $topic->id }}" {{ $video->topic_id == $topic->id ? 'selected' : '' }}>
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
                                <option value="{{ $guide->id }}" {{ $video->guide_id == $guide->id ? 'selected' : '' }}>
                                    {{ $guide->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="plan">Plan</label>
                        <select name="plan" class="form-control">
                            <option value="">Select Plan</option>
                            <option value="new" {{ $video->plan === 'new' ? 'selected' : '' }}>New</option>
                            <option value="free" {{ $video->plan === 'free' ? 'selected' : '' }}>Free</option>
                            <option value="premium" {{ $video->plan === 'premium' ? 'selected' : '' }}>Premium</option>
                        </select>
                    </div>


                    <!-- Video Title -->
                    <div class="form-group">
                        <label for="title">Video Title</label>
                        <input type="text" name="title" class="form-control" id="title"
                            value="{{ $video->title }}" required>
                    </div>

                    <!-- Video Description -->
                    <div class="form-group">
                        <label for="description">Video Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5" required>{{ $video->description }}</textarea>
                    </div>

                    <!-- The existing video information -->
                    <div class="form-group">
                        <label for="current_video">Current Video:</label>
                        <div style="position: relative; width: 200px; height: 200px; overflow: hidden;">
                            <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                                src="https://www.youtube.com/embed/{{ $video->video }}" frameborder="0"
                                allowfullscreen></iframe>
                        </div>
                    </div>

                    <!-- <div class="form-group">
                                    <label for="guide_id">Status</label>
                                    <select name="status" id="guide_id" class="form-control" required>
                                        <option value="">Select Status</option>
                                        <option value="public" {{ $video->status == 'public' ? 'selected' : '' }}>
                                            Public
                                        </option>
                                        <option value="private" {{ $video->status == 'private' ? 'selected' : '' }}>
                                            Private
                                        </option>
                                        <option value="schedule" {{ $video->status == 'schedule' ? 'selected' : '' }}>
                                            Schedule
                                        </option>
                                    </select>
                                </div> -->

                    <!-- No video upload field to prevent updating the video file -->

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
