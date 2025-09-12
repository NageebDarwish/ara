@extends('admin.layout.layout')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create Video</h3>
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

                <form action="{{ route('admin.video.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    
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
                    </div>

                    <div class="form-group">
                        <label for="country_id">Plan</label>
                        <select name="country_id" class="form-control">
                                <option value="free">Free</option>
                                <option value="premium">Premium</option>
                        </select>
                    </div>

                    <!-- Video Title -->
                    <div class="form-group">
                        <label for="title">Video Title</label>
                        <input type="text" name="title" class="form-control" id="title"
                            value="{{ old('title') }}" required>
                    </div>

                    <!-- Video Description -->
                    <div class="form-group">
                        <label for="description">Video Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
                    </div>

                    <!-- Video File Upload -->
                    <div class="form-group">
                        <label for="video">Upload Video</label>
                        <input type="file" name="video" class="form-control-file" id="video" accept="video/*"
                            required>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
