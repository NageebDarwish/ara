@extends('admin.layout.layout')

@section('content')
    <x-dynamic-table
        title="Topics"
        :createRoute="route('admin.topic.create')"
        createButtonText="Create Topic"
        tableId="topicsTable"
        :enableAjaxPagination="true"
        :ajaxUrl="route('admin.topic.data')"
        :columns="[
            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
            ['label' => 'Name', 'key' => 'name', 'sortable' => true, 'searchable' => true],
            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
        ]"
        :data="collect([])"
    />
@endsection
