@extends('admin.layout.layout')

@section('content')
    <x-dynamic-table
        title="Levels"
        :createRoute="route('admin.levels.create')"
        createButtonText="Create Level"
        tableId="levelsTable"
        :enableAjaxPagination="true"
        :ajaxUrl="route('admin.levels.data')"
        :columns="[
            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
            ['label' => 'Name', 'key' => 'name', 'sortable' => true, 'searchable' => true],
            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
        ]"
        :data="collect([])"
    />
@endsection
