@extends('admin.layout.layout')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4 shadow">
                    <div class="card-header p-0 position-relative  ">
                        <div
                            class="bg-gradient-light shadow-primary border-radius-lg p-3 d-flex justify-content-between align-items-center">
                            <h6 class="text-dark">Blog Posts</h6>
                            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary ">
                                <i class="material-icons"></i> Create Blog
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive p-0">
                            <table id="dataTable" class="table align-items-center mb-0">
                                <thead>
                                      
                                    <tr>
                                             <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Category</th>
                                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Author</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Title</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Slug</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Created</th>
                                        <th
                                            class="text-secondary opacity-7 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $blog)
                                        <tr>
                                                   <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $blog->category->name }}</p>
                                            </td>
                                             <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $blog->author }}</p>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="{{ asset($blog->cover_image) }}"
                                                            class="avatar avatar-sm me-3 border-radius-lg"
                                                            alt="{{ $blog->title }}">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center mx-2">
                                                        <h6 class="mb-0 text-sm">{{ $blog->title }}</h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ Str::limit($blog->meta_title, 30) }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $blog->slug }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $blog->created_at->format('d M Y') }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex">
                                                    <a href="{{ route('admin.blog.edit', $blog->id) }}"
                                                        class="btn btn-sm btn-outline-primary me-2" data-toggle="tooltip"
                                                        title="Edit">
                                                        <i class="material-icons">edit</i>
                                                    </a>
                                                    <form action="{{ route('admin.blog.destroy', $blog->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger mx-2"
                                                            data-toggle="tooltip" title="Delete"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="material-icons">delete</i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No blog posts found.</td>
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
    <script>
        // Initialize tooltips
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true
            });
        });
    </script>
@endsection
