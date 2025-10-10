@extends('admin.layout.layout')

@section('content')
    <x-dynamic-table
        title="Email Templates"
        :showCreateButton="false"
        tableId="emailTemplatesTable"
        :enableAjaxPagination="true"
        :ajaxUrl="route('admin.email-templates.data')"
        :columns="[
            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
            ['label' => 'Name', 'key' => 'name', 'sortable' => true, 'searchable' => true],
            ['label' => 'Subject', 'key' => 'subject', 'sortable' => true, 'searchable' => true],
            ['label' => 'Trigger Event', 'key' => 'trigger', 'sortable' => false, 'searchable' => false],
            ['label' => 'Status', 'key' => 'status', 'sortable' => false, 'searchable' => false],
            ['label' => 'Sent Count', 'key' => 'sent_count', 'sortable' => false, 'searchable' => false],
            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
        ]"
        :data="collect([])"
    />

    <script>
        $(document).ready(function() {
            // Toggle status
            $(document).on('click', '.toggle-status-btn', function(e) {
                e.preventDefault();
                const templateId = $(this).data('id');

                $.ajax({
                    url: '{{ route("admin.email-templates.toggle-status") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: templateId
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message, 'success');
                            $('#emailTemplatesTable').DataTable().ajax.reload();
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
