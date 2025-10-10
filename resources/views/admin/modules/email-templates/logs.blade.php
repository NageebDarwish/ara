@extends('admin.layout.layout')

@section('content')
    <div class="mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa fa-history"></i> Email Logs: {{ $template->name }}
                </h3>
                <a href="{{ route('admin.email-templates.edit', $template->id) }}" class="btn btn-secondary float-right">Back to Template</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Recipient</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Sent At</th>
                                <th>Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->recipient_email }}</td>
                                    <td>{{ $log->user ? $log->user->name : 'N/A' }}</td>
                                    <td>
                                        @if($log->status === 'sent')
                                            <span class="badge badge-success">Sent</span>
                                        @elseif($log->status === 'failed')
                                            <span class="badge badge-danger">Failed</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>
                                        @if($log->error_message)
                                            <small class="text-danger">{{ $log->error_message }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No email logs found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

