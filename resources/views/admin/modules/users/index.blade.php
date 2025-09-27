@extends('admin.layout.layout')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Management</h3>
            </div>
            <div class="card-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" id="userManagementTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" role="tab">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="managers-tab" data-toggle="tab" href="#managers" role="tab">Managers</a>
                    </li>
                    @if(auth()->user()->hasRole('super-admin'))
                    <li class="nav-item">
                        <a class="nav-link" id="permissions-tab" data-toggle="tab" href="#permissions" role="tab">Permissions</a>
                    </li>
                    @endif
                </ul>

                <!-- Tab Content -->
                <div class="tab-content pt-3" id="userManagementTabsContent">
                    <!-- Users Tab -->
                    <div class="tab-pane fade show active" id="users" role="tabpanel">
                        <div class="table-responsive">
                            <table id="usersTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Premium</th>
                                        <th>Progress Level</th>
                                        <th>Total Watching Hours</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users->where('role', 'user') as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->is_premium == 1 ? 'Yes' : 'No' }}</td>
                                            <td>{{ $user->progressLevel->name ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $totalSeconds = $user->total_watching_hours;
                                                    $hours = floor($totalSeconds / 3600);
                                                    $minutes = floor(($totalSeconds % 3600) / 60);
                                                    $seconds = $totalSeconds % 60;
                                                @endphp
                                                {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                                            </td>
                                            <td>
                                                @if (auth()->user()->role == 'admin')
                                                    <form action="{{ route('admin.users.delete', $user->id) }}"
                                                        method="POST" style="display:inline-block;"
                                                        onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Managers Tab -->
                    <div class="tab-pane fade" id="managers" role="tabpanel">
                        <div class="mb-3">
                            <a href="{{ route('admin.manager.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Manager
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table id="managersTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users->where('role', 'manager') as $manager)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $manager->name }}</td>
                                            <td>{{ $manager->email }}</td>
                                            <td>
                                                <a href="{{ route('admin.manager.edit', $manager->id) }}"
                                                    class="btn btn-sm btn-warning mr-2">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.manager.destroy', $manager->id) }}"
                                                    method="POST" style="display:inline-block;"
                                                    onsubmit="return confirm('Are you sure you want to delete this manager?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Permissions Tab -->
                    @if(auth()->user()->hasRole('super-admin'))
                    <div class="tab-pane fade" id="permissions" role="tabpanel">
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Manage Admin Permissions</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Admin</th>
                                                        @foreach(\Spatie\Permission\Models\Permission::all() as $permission)
                                                            <th>{{ ucwords(str_replace('_', ' ', $permission->name)) }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($users->where('role', 'manager') as $admin)
                                                        <tr>
                                                            <td>{{ $admin->name }}</td>
                                                            @foreach(\Spatie\Permission\Models\Permission::all() as $permission)
                                                                <td>
                                                                    <form action="{{ route('admin.permissions.toggle') }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="user_id" value="{{ $admin->id }}">
                                                                        <input type="hidden" name="permission" value="{{ $permission->name }}">
                                                                        <div class="custom-control custom-switch">
                                                                            <input type="checkbox" class="custom-control-input permission-toggle" 
                                                                                id="permission_{{ $admin->id }}_{{ $permission->id }}"
                                                                                {{ $admin->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                                                onchange="this.form.submit()">
                                                                            <label class="custom-control-label" for="permission_{{ $admin->id }}_{{ $permission->id }}"></label>
                                                                        </div>
                                                                    </form>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true
            });

            $('#managersTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true
            });
        });
    </script>

    <style>
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link.active {
            color: #007bff;
            background-color: transparent;
            border-bottom: 3px solid #007bff;
        }

        .nav-tabs .nav-link:hover:not(.active) {
            border-bottom: 3px solid #dee2e6;
        }
    </style>
@endsection
