@extends('admin.layout.layout')

@section('content')
    <x-dynamic-table
        title="Categories"
        :createRoute="route('admin.category.create')"
        createButtonText="Create Category"
        tableId="categoriesTable"
        :enableAjaxPagination="true"
        :ajaxUrl="route('admin.category.data')"
        :columns="[
            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
            ['label' => 'Name', 'key' => 'name', 'sortable' => true, 'searchable' => true],
            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
        ]"
        :data="collect([])"
    />
@endsection
