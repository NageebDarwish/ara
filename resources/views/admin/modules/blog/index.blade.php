@extends('admin.layout.layout')

@section('content')
    <x-dynamic-table
        title="Blog Posts"
        :createRoute="route('admin.blog.create')"
        createButtonText="Create Blog"
        tableId="blogsTable"
        :enableAjaxPagination="true"
        :ajaxUrl="route('admin.blog.data')"
        :columns="[
            ['label' => 'Category', 'key' => 'category_name', 'sortable' => false, 'searchable' => false],
            ['label' => 'Author', 'key' => 'author', 'sortable' => true, 'searchable' => true],
            ['label' => 'Title', 'key' => 'title_with_image', 'sortable' => false, 'searchable' => false],
            ['label' => 'Slug', 'key' => 'slug', 'sortable' => true, 'searchable' => true],
            ['label' => 'Created', 'key' => 'created', 'sortable' => false, 'searchable' => false],
            ['label' => 'Action', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
        ]"
        :data="collect([])"
    />
@endsection
