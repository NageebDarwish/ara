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
            ['label' => 'Status', 'key' => 'status_badge', 'sortable' => false, 'searchable' => false],
            ['label' => 'Publish Date', 'key' => 'publish_date', 'sortable' => false, 'searchable' => false],
            ['label' => 'Created', 'key' => 'created', 'sortable' => false, 'searchable' => false],
            ['label' => 'Action', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
        ]"
        :data="collect([])"
    />

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Convert UTC times to local timezone
    function convertUTCToLocal() {
        document.querySelectorAll('.local-time').forEach(function(element) {
            const utcTime = element.getAttribute('data-utc');
            if (utcTime) {
                const date = new Date(utcTime);
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                element.textContent = date.toLocaleString('en-US', options);
            }
        });
    }

    // Run on page load
    convertUTCToLocal();

    // Run after DataTables draws (for AJAX loaded data)
    if (window.blogsTable) {
        window.blogsTable.on('draw', function() {
            convertUTCToLocal();
        });
    }

    // Also listen for DataTables draw event on the table element
    $('#blogsTable').on('draw.dt', function() {
        setTimeout(convertUTCToLocal, 100);
            });
        });
    </script>
@endsection
