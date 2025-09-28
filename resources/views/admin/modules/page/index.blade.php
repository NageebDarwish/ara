@extends('admin.layout.layout')

@section('content')
    <div class="mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pages</h3>

            </div>
            <div class="card-body table-responsive">
                <table class="table  table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $page)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $page->name }}</td>
                                <td>{{ $page->slug }}</td>
                                <td>{{ $page->title }}</td>
                                <td>{{ $page->description }}</td>
                                <td>
                                    <a href="{{ route('admin.page.edit', $page->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No pges found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
