@extends('admin.layout.layout')

@section('content')
    <div class="mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Guide</h3>
                <a href="{{ route('admin.guides.index') }}" class="btn btn-secondary float-right">Back</a>
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

                <form action="{{ route('admin.guides.update', $guide->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Guide Name</label>
                        <input type="text" name="name" class="form-control" id="name"
                            value="{{ old('name', $guide->name) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
