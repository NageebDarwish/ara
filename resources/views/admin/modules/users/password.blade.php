@extends('admin.layout.layout')

@section('content')
<div class="card mt-5">
    <div class="card-header">
        <h5>Update Password</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.password.update', $user->id) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection


