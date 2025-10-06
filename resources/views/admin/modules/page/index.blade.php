@extends('admin.layout.layout')

@section('content')
    <x-dynamic-table
        title="Pages"
        :showCreateButton="false"
        tableId="pagesTable"
        :enableAjaxPagination="true"
        :ajaxUrl="route('admin.page.data')"
        :columns="[
            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
            ['label' => 'Name', 'key' => 'name', 'sortable' => true, 'searchable' => true],
            ['label' => 'Slug', 'key' => 'slug', 'sortable' => true, 'searchable' => true],
            ['label' => 'Title', 'key' => 'title', 'sortable' => true, 'searchable' => true],
            ['label' => 'Description', 'key' => 'description', 'sortable' => false, 'searchable' => true],
            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
        ]"
        :data="collect([])"
    />
@endsection
