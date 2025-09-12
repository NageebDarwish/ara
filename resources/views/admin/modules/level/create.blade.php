@extends('admin.layout.layout')


@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create Level</h3>
                <a href="{{ route('admin.levels.index') }}" class="btn btn-secondary float-right">Back</a>
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

                <form action="{{ route('admin.levels.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Topic Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
