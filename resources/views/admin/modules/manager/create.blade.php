@extends('admin.layout.layout')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header ">
                <h3 class="card-title">Add New Manager</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.manager.store') }}" method="POST">
                    @csrf

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>





                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Manager
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }
    </style>
@endsection
