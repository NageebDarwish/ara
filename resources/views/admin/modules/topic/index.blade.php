@extends('admin.layout.layout')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Topics</h3>
                <a href="{{ route('admin.topic.create') }}" class="btn btn-primary float-right">Create Topic</a>
            </div>
            <div class="card-body table-responsive">
                <table id="dataTable" class="table  table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $topic)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $topic->name }}</td>
                                <td>
                                    <a href="{{ route('admin.topic.edit', $topic->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.topic.destroy', $topic->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No topics found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
