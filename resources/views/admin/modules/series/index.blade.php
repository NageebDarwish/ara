@extends('admin.layout.layout')

@section('content')
    <x-dynamic-table
        title="Series"
        :showCreateButton="false"
        tableId="seriesTable"
        :enableAjaxPagination="true"
        :ajaxUrl="route('admin.series.data')"
        :columns="[
            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
            ['label' => 'Title', 'key' => 'title', 'sortable' => true, 'searchable' => true],
            ['label' => 'Level', 'key' => 'level', 'sortable' => false, 'searchable' => false],
            ['label' => 'Publish Date', 'key' => 'publishDate', 'sortable' => false, 'searchable' => false],
            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
        ]"
        :data="collect([])"
    />

<!-- Series Videos Modal -->
<div class="modal fade" id="seriesVideosModal" tabindex="-1" role="dialog" aria-labelledby="seriesVideosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
                                        <div class="modal-header">
                <h5 class="modal-title" id="seriesVideosModalLabel">Series Videos</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                <div class="list-group" id="seriesVideosList"></div>
            </div>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
    // Handle View Videos button click
    $(document).on('click', '.view-videos-btn', function(e) {
        e.preventDefault();
        const seriesId = $(this).data('series-id');
        const seriesTitle = $(this).data('series-title');

        $('#seriesVideosModalLabel').text(seriesTitle + ' - Related Videos');
        $('#seriesVideosList').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');

        // Fetch videos via AJAX
            $.ajax({
            url: '{{ route('admin.series.getVideosWithPlan') }}',
            type: 'GET',
            data: { series_id: seriesId },
                success: function(response) {
                if (response.success && response.data.videos.length > 0) {
                    let html = '';
                    response.data.videos.forEach(function(video, index) {
                        // Determine badge class based on plan
                        let badgeClass = 'badge-secondary';
                        if (video.plan === 'premium') badgeClass = 'badge-success';
                        else if (video.plan === 'free') badgeClass = 'badge-warning';
                        else if (video.plan === 'new') badgeClass = 'badge-info';

                        html += `
                            <div class="card mb-3 ${video.plan === 'new' ? 'border-info' : ''}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h5 class="card-title">${video.title}</h5>
                                            <p class="card-text text-muted small">${video.description || 'No description'}</p>
                                        </div>
                                        <span class="badge ${badgeClass} ml-2">${video.plan ? video.plan.charAt(0).toUpperCase() + video.plan.slice(1) : 'N/A'}</span>
                                    </div>
                                    ${video.plan === 'new' ? '<div class="alert alert-info alert-sm mt-2 mb-2"><small><i class="fa fa-info-circle"></i> This video needs to be assigned a plan</small></div>' : ''}
                                    <div class="mt-3">
                                        <label class="font-weight-bold small">Assign Plan:</label>
                                        <select class="form-control form-control-sm d-inline-block w-auto plan-select" data-video-id="${video.id}">
                                            <option value="new" ${video.plan === 'new' ? 'selected' : ''}>New (Not Visible)</option>
                                            <option value="free" ${video.plan === 'free' ? 'selected' : ''}>Free</option>
                                            <option value="premium" ${video.plan === 'premium' ? 'selected' : ''}>Premium</option>
                                        </select>
                                </div>
                            </div>
                        </div>
                    `;
                        });
                    $('#seriesVideosList').html(html);
                    } else {
                    $('#seriesVideosList').html('<p class="text-center">No videos found for this series.</p>');
                    }
                },
                error: function() {
                $('#seriesVideosList').html('<p class="text-center text-danger">Error loading videos.</p>');
            }
        });

        $('#seriesVideosModal').modal('show');
    });

    // Handle plan change
    $(document).on('change', '.plan-select', function() {
        const videoId = $(this).data('video-id');
        const newPlan = $(this).val();

        $.ajax({
            url: '{{ route('admin.series.updatePlan') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                video_id: videoId,
                plan: newPlan
            },
            success: function(response) {
                if (response.success) {
                    showToast('Plan updated successfully', 'success');
                }
            },
            error: function() {
                showToast('Failed to update plan', 'error');
            }
        });
    });
        });
    </script>
@endsection
