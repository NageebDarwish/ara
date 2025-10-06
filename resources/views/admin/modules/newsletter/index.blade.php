@extends('admin.layout.layout')

@section('content')
    <x-dynamic-table
        title="Newsletters"
        :createRoute="route('admin.newsletter.create')"
        createButtonText="Create Newsletter"
        tableId="newslettersTable"
        :enableAjaxPagination="true"
        :ajaxUrl="route('admin.newsletter.data')"
        :columns="[
            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
            ['label' => 'Subject', 'key' => 'subject', 'sortable' => true, 'searchable' => true],
            ['label' => 'Recipients', 'key' => 'recipient_info', 'sortable' => false, 'searchable' => false],
            ['label' => 'Status', 'key' => 'status_badge', 'sortable' => false, 'searchable' => false],
            ['label' => 'Scheduled For', 'key' => 'scheduled_date', 'sortable' => false, 'searchable' => false],
            ['label' => 'Sent At', 'key' => 'sent_date', 'sortable' => false, 'searchable' => false],
            ['label' => 'Sent Count', 'key' => 'recipients_count', 'sortable' => true, 'searchable' => false],
            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
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
    $('#newslettersTable').on('draw.dt', function() {
        setTimeout(convertUTCToLocal, 100);
    });
});
</script>
@endsection
