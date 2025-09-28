@extends('admin.layout.layout')

@section('content')
    <div class="">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Category Details</h6>
                <div>
                    <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-warning btn-sm">
                        <i class="material-icons">edit</i> Edit
                    </a>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-secondary btn-sm">
                        <i class="material-icons">arrow_back</i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Category Name:</label>
                            <p class="form-control-plaintext">{{ $category->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Created At:</label>
                            <p class="form-control-plaintext">{{ $category->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Updated At:</label>
                            <p class="form-control-plaintext">{{ $category->updated_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Total Blogs:</label>
                            <p class="form-control-plaintext">{{ $category->blogs->count() ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection