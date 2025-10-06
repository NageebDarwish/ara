@extends('admin.layout.layout')

@section('content')
    @php
        // Define columns for users table
        $userColumns = [
            [
                'label' => '#',
                'field' => 'id',
                'type' => 'custom',
                'render' => function ($item) use ($users) {
                    $usersList = $users->where('role', 'user');
                    $index = $usersList->search(function ($user) use ($item) {
                        return $user->id === $item->id;
                    });
                    return $index !== false ? $index + 1 : 0;
                },
            ],
            ['label' => 'Name', 'field' => 'name'],
            ['label' => 'Email', 'field' => 'email'],
            [
                'label' => 'Premium',
                'field' => 'is_premium',
                'type' => 'boolean',
                'true_text' => 'Yes',
                'false_text' => 'No',
            ],
            ['label' => 'Progress Level', 'field' => 'progressLevel.name', 'type' => 'relation'],
            [
                'label' => 'Total Watching Hours',
                'field' => 'total_watching_hours',
                'type' => 'custom',
                'render' => function ($item) {
                    $totalSeconds = $item->total_watching_hours;
                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds % 3600) / 60);
                    $seconds = $totalSeconds % 60;
                    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                },
            ],
        ];

        // Define actions for users table
        $userActions = [];
        if (auth()->user()->role == 'admin') {
            $userActions[] = [
                'type' => 'form',
                'route' => 'admin.users.delete',
                'method' => 'DELETE',
                'class' => 'btn-danger',
                'label' => 'Delete',
                'confirm' => 'Are you sure you want to delete this user?',
            ];
        }

        // Define columns for managers table
        $managerColumns = [
            [
                'label' => '#',
                'field' => 'id',
                'type' => 'custom',
                'render' => function ($item) use ($users) {
                    $managersList = $users->where('role', 'manager');
                    $index = $managersList->search(function ($user) use ($item) {
                        return $user->id === $item->id;
                    });
                    return $index !== false ? $index + 1 : 0;
                },
            ],
            ['label' => 'Name', 'field' => 'name'],
            ['label' => 'Email', 'field' => 'email'],
        ];

        // Define actions for managers table
        $managerActions = [
            [
                'type' => 'link',
                'route' => 'admin.manager.edit',
                'class' => 'btn-warning',
                'label' => 'Edit',
                'icon' => 'fas fa-edit',
                'spacing' => 'me-2',
            ],
            [
                'type' => 'form',
                'route' => 'admin.manager.destroy',
                'method' => 'DELETE',
                'class' => 'btn-danger',
                'label' => 'Delete',
                'confirm' => 'Are you sure you want to delete this manager?',
            ],
        ];

        // Define tabs data
        $tabs = [
            'users' => [
                'title' => 'Users',
                // Using server-side AJAX, keep initial data empty to avoid column mismatch
                'data' => collect([]),
                'columns' => [
                    ['key' => 'DT_RowIndex', 'label' => '#', 'sortable' => false, 'searchable' => false],
                    ['key' => 'name', 'label' => 'Name', 'sortable' => true, 'searchable' => true],
                    ['key' => 'email', 'label' => 'Email', 'sortable' => true, 'searchable' => true],
                    ['key' => 'is_premium', 'label' => 'Premium', 'sortable' => true, 'searchable' => false],
                    ['key' => 'progress_level', 'label' => 'Progress Level', 'sortable' => true, 'searchable' => false],
                    [
                        'key' => 'total_watching_hours',
                        'label' => 'Total Watching Hours',
                        'sortable' => true,
                        'searchable' => false,
                    ],
                    ['key' => 'actions', 'label' => 'Actions', 'sortable' => false, 'searchable' => false],
                ],
                // Actions are rendered by DataTables server-side; omit blade actions to prevent extra column
                'actions' => [],
                'ajaxUrl' => route('admin.users.data', ['tab' => 'users']),
            ],
            'managers' => [
                'title' => 'Managers',
                'data' => collect([]),
                'columns' => [
                    ['key' => 'DT_RowIndex', 'label' => '#', 'sortable' => false, 'searchable' => false],
                    ['key' => 'name', 'label' => 'Name', 'sortable' => true, 'searchable' => true],
                    ['key' => 'email', 'label' => 'Email', 'sortable' => true, 'searchable' => true],
                    ['key' => 'actions', 'label' => 'Actions', 'sortable' => false, 'searchable' => false],
                ],
                'actions' => [],
                'ajaxUrl' => route('admin.users.data', ['tab' => 'managers']),
            ],
        ];

        if (auth()->user()->hasRole('super-admin')) {
            // Define columns for permissions table
            $permissionColumns = [['label' => 'Admin', 'key' => 'name']];

            // Add permission columns dynamically
            foreach (\Spatie\Permission\Models\Permission::all() as $permission) {
                $permissionColumns[] = [
                    'label' => ucwords(str_replace('_', ' ', $permission->name)),
                    'key' => 'permission_' . $permission->id,
                    'type' => 'custom',
                    'render' => function ($admin) use ($permission) {
                        return '
                             <form action="' .
                            route('admin.permissions.toggle') .
                            '" method="POST" style="display:inline;">
                                 ' .
                            csrf_field() .
                            '
                                 <input type="hidden" name="user_id" value="' .
                            $admin->id .
                            '">
                                 <input type="hidden" name="permission" value="' .
                            $permission->name .
                            '">
                                 <div class="custom-control custom-switch">
                                     <input type="checkbox" class="custom-control-input permission-toggle"
                                         id="permission_' .
                            $admin->id .
                            '_' .
                            $permission->id .
                            '"
                                         ' .
                            ($admin->hasPermissionTo($permission->name) ? 'checked' : '') .
                            '
                                         onchange="this.form.submit()">
                                     <label class="custom-control-label" for="permission_' .
                            $admin->id .
                            '_' .
                            $permission->id .
                            '"></label>
                                 </div>
                             </form>
                         ';
                    },
                ];
            }

            $tabs['permissions'] = [
                'title' => 'Permissions',
                'data' => $users->where('role', 'manager'),
                'columns' => $permissionColumns,
                'actions' => [],
            ];
        }
    @endphp

    <x-dynamic-table title="User Management" :tabs="$tabs" tableId="userManagementTable" :showCreateButton="false"
        cardClass="card mt-5" :enableAjaxPagination="true" />

    <!-- Add Manager Button for Managers Tab -->
    <script>
        $(document).ready(function() {
            // Add create button to managers tab
            const managersTabPane = $('#managers');
            const createButton = `
                <div class="mb-3">
                    <a href="{{ route('admin.manager.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Manager
                    </a>
                </div>
            `;
            managersTabPane.prepend(createButton);
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
