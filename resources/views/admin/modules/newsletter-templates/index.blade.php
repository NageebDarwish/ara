@extends('admin.layout.layout')

@section('content')
    <x-dynamic-table
        title="Newsletter Templates"
        :showCreateButton="false"
        tableId="newsletterTemplatesTable"
        :enableAjaxPagination="true"
        :ajaxUrl="route('admin.newsletter-templates.data')"
        :columns="[
            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
            ['label' => 'Name', 'key' => 'name', 'sortable' => true, 'searchable' => true],
            ['label' => 'Subject', 'key' => 'subject', 'sortable' => true, 'searchable' => true],
            ['label' => 'Status', 'key' => 'status', 'sortable' => false, 'searchable' => false],
            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
        ]"
        :data="collect([])"
    />

    <script>
        $(document).ready(function() {
            $(document).on('click', '.toggle-status-btn', function(e) {
                e.preventDefault();
                const templateId = $(this).data('id');

                $.ajax({
                    url: '{{ route("admin.newsletter-templates.toggle-status") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: templateId
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message, 'success');
                            $('#newsletterTemplatesTable').DataTable().ajax.reload();
                        }
                    },
                    error: function() {
                        showToast('Failed to update status', 'error');
                    }
                });
            });
        });
    </script>
@endsection

