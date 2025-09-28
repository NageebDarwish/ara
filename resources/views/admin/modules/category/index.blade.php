@extends('admin.layout.layout')

@section('content')
    <div class="py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4 shadow">
                    <div class="card-header p-0 position-relative">
                        <div
                            class="bg-gradient-light shadow-primary border-radius-lg p-3 d-flex justify-content-between align-items-center">
                            <h6 class="text-dark">Categories</h6>
                            <a href="{{ route('admin.category.create') }}" class="btn btn-primary">
                                <i class="material-icons"></i> Create Category
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive p-0">
                            <table id="dataTable" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Name</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Created</th>
                                        <th
                                            class="text-secondary opacity-7 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $category)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $category->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $category->created_at->format('Y-m-d') }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex">
                                                    <a href="{{ route('admin.category.show', $category->id) }}"
                                                        class="btn btn-info btn-sm me-2">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                    <a href="{{ route('admin.category.edit', $category->id) }}"
                                                        class="btn btn-warning btn-sm me-2">
                                                        <i class="material-icons">edit</i>
                                                    </a>
                                                    <form action="{{ route('admin.category.destroy', $category->id) }}"
                                                        method="POST" style="display: inline-block;"
                                                        onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="material-icons">delete</i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No categories found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection