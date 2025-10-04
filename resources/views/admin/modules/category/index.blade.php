@extends('admin.layout.layout')

@section('content')
    @php
        // Define columns for categories table
        $columns = [
            [
                'label' => 'Name',
                'field' => 'name',
                'type' => 'custom',
                'render' => function($item) {
                    return '<div class="d-flex px-2 py-1">
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">' . $item->name . '</h6>
                                </div>
                            </div>';
                }
            ],
            [
                'label' => 'Created',
                'field' => 'created_at',
                'type' => 'date',
                'format' => 'Y-m-d',
                'td_class' => 'align-middle text-center text-sm'
            ]
        ];

        // Define actions for categories table
        $actions = [
            [
                'type' => 'link',
                'route' => 'admin.category.show',
                'class' => 'btn-info btn-sm',
                'icon' => 'material-icons',
                'label' => 'visibility',
                'spacing' => 'me-2'
            ],
            [
                'type' => 'link',
                'route' => 'admin.category.edit',
                'class' => 'btn-warning btn-sm',
                'icon' => 'material-icons',
                'label' => 'edit',
                'spacing' => 'me-2'
            ],
            [
                'type' => 'form',
                'route' => 'admin.category.destroy',
                'method' => 'DELETE',
                'class' => 'btn-danger btn-sm',
                'icon' => 'material-icons',
                'label' => 'delete',
                'confirm' => 'Are you sure you want to delete this category?'
            ]
        ];
    @endphp

    <x-dynamic-table 
        title="Categories"
        :columns="$columns"
        :data="$data"
        :actions="$actions"
        createRoute="{{ route('admin.category.create') }}"
        createButtonText="Create Category"
        tableId="dataTable"
        emptyMessage="No categories found"
    />
@endsection