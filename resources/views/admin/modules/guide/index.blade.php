@extends('admin.layout.layout')

@section('content')
    <x-dynamic-table
        title="Guides"
        :createRoute="route('admin.guides.create')"
        createButtonText="Create Guide"
        tableId="guidesTable"
        :enableAjaxPagination="true"
        :ajaxUrl="route('admin.guides.data')"
        :columns="[
            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
            ['label' => 'Name', 'key' => 'name', 'sortable' => true, 'searchable' => true],
            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
        ]"
        :data="collect([])"
    />
@endsection
