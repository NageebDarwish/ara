@extends('admin.layout.layout')

@section('content')
    <x-dynamic-table
                title="Videos"
                :showCreateButton="false"
                tableId="videosTable"
                :enableAjaxPagination="true"
                :tabs="[
                    'new' => [
                        'title' => 'New',
                        'ajaxUrl' => route('admin.video.data', ['plan' => 'new']),
                        'columns' => [
                            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Title', 'key' => 'title', 'sortable' => true, 'searchable' => true],
                            ['label' => 'Published Date', 'key' => 'publishedDate', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Level', 'key' => 'level', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Topic', 'key' => 'topic', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Guide', 'key' => 'guide', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Plan', 'key' => 'plan_badge', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Video', 'key' => 'video_link', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Duration', 'key' => 'duration', 'sortable' => false, 'searchable' => false],
                        ],
                        'data' => collect([])
                    ],
                    'free' => [
                        'title' => 'Free',
                        'ajaxUrl' => route('admin.video.data', ['plan' => 'free']),
                        'columns' => [
                            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Title', 'key' => 'title', 'sortable' => true, 'searchable' => true],
                            ['label' => 'Published Date', 'key' => 'publishedDate', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Level', 'key' => 'level', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Topic', 'key' => 'topic', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Guide', 'key' => 'guide', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Plan', 'key' => 'plan_badge', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Video', 'key' => 'video_link', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Duration', 'key' => 'duration', 'sortable' => false, 'searchable' => false],
                        ],
                        'data' => collect([])
                    ],
                    'premium' => [
                        'title' => 'Premium',
                        'ajaxUrl' => route('admin.video.data', ['plan' => 'premium']),
                        'columns' => [
                            ['label' => '#', 'key' => 'DT_RowIndex', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Title', 'key' => 'title', 'sortable' => true, 'searchable' => true],
                            ['label' => 'Published Date', 'key' => 'publishedDate', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Actions', 'key' => 'actions', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Level', 'key' => 'level', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Topic', 'key' => 'topic', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Guide', 'key' => 'guide', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Plan', 'key' => 'plan_badge', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Video', 'key' => 'video_link', 'sortable' => false, 'searchable' => false],
                            ['label' => 'Duration', 'key' => 'duration', 'sortable' => false, 'searchable' => false],
                        ],
                        'data' => collect([])
                    ]
                ]"
    />

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="videoContainer"></div>
            </div>
        </div>
    </div>
</div>

<script>
function openVideoModal(videoId, title) {
    document.getElementById('videoModalLabel').textContent = title;
    document.getElementById('videoContainer').innerHTML = '<iframe class="w-100" height="400" src="https://www.youtube.com/embed/' + videoId + '" frameborder="0" allowfullscreen></iframe>';
    $('#videoModal').modal('show');
}

// Clear video when modal closes
$('#videoModal').on('hidden.bs.modal', function () {
    document.getElementById('videoContainer').innerHTML = '';
});
</script>
@endsection
