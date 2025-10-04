@props([
    'title' => 'Data Table',
    'createRoute' => null,
    'createButtonText' => 'Create New',
    'columns' => [],
    'data' => [],
    'actions' => [],
    'tableId' => 'dataTable',
    'emptyMessage' => 'No data found',
    'showCreateButton' => true,
    'cardClass' => 'card my-4 shadow',
    'tableClass' => 'table align-items-center mb-0',
    'enableDataTable' => true,
    'tabs' => null,
    'ajaxUrl' => null,
    'enableAjaxPagination' => false
])

<div class="py-4">
    <div class="row">
        <div class="col-12">
            <div class="{{ $cardClass }}">
                <div class="card-header p-0 position-relative">
                    <div class="bg-gradient-light shadow-primary border-radius-lg p-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-dark">{{ $title }}</h6>
                        @if($showCreateButton && $createRoute)
                            <a href="{{ $createRoute }}" class="btn btn-primary">
                                <i class="material-icons"></i> {{ $createButtonText }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($tabs)
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs" id="{{ $tableId }}Tabs" role="tablist">
                            @foreach($tabs as $key => $tab)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                       id="{{ $key }}-tab"
                                       data-toggle="tab"
                                       href="#{{ $key }}"
                                       role="tab">{{ $tab['title'] }}</a>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content pt-3" id="{{ $tableId }}TabsContent">
                            @foreach($tabs as $key => $tab)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                     id="{{ $key }}"
                                     role="tabpanel">
                                    @include('components.table-content', [
                                        'columns' => $tab['columns'] ?? $columns,
                                        'data' => $tab['data'],
                                        'actions' => $tab['actions'] ?? $actions,
                                        'tableId' => $tableId . '-' . $key,
                                        'tableClass' => $tableClass,
                                        'emptyMessage' => $emptyMessage,
                                        'enableDataTable' => $enableDataTable
                                    ])
                                </div>
                            @endforeach
                        </div>
                    @else
                        @include('components.table-content', [
                            'columns' => $columns,
                            'data' => $data,
                            'actions' => $actions,
                            'tableId' => $tableId,
                            'tableClass' => $tableClass,
                            'emptyMessage' => $emptyMessage,
                            'enableDataTable' => $enableDataTable
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($enableDataTable)
    @push('scripts')
    <script>
        $(document).ready(function() {
            @if($tabs)
                @foreach($tabs as $key => $tab)
                    @if($enableAjaxPagination && isset($tab['ajaxUrl']))
                        $('#{{ $tableId }}-{{ $key }}').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{{ $tab['ajaxUrl'] }}',
                                type: 'GET',
                                data: function(d) {
                                    d.tab = '{{ $key }}';
                                }
                            },
                            columns: [
                                @foreach($tab['columns'] as $column)
                                    {
                                        data: '{{ $column['key'] }}',
                                        name: '{{ $column['key'] }}',
                                        orderable: {{ isset($column['sortable']) && $column['sortable'] ? 'true' : 'false' }},
                                        searchable: {{ isset($column['searchable']) && $column['searchable'] ? 'true' : 'false' }}
                                    },
                                @endforeach
                            ],
                            paging: true,
                            searching: true,
                            ordering: true,
                            responsive: true,
                            pageLength: 10,
                            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
                        });
                    @else
                        $('#{{ $tableId }}-{{ $key }}').DataTable({
                            paging: true,
                            searching: true,
                            ordering: true,
                            responsive: true
                        });
                    @endif
                @endforeach
            @else
                @if($enableAjaxPagination && $ajaxUrl)
                    $('#{{ $tableId }}').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ $ajaxUrl }}',
                            type: 'GET'
                        },
                        columns: [
                            @foreach($columns as $column)
                                {
                                    data: '{{ $column['key'] }}',
                                    name: '{{ $column['key'] }}',
                                    orderable: {{ isset($column['sortable']) && $column['sortable'] ? 'true' : 'false' }},
                                    searchable: {{ isset($column['searchable']) && $column['searchable'] ? 'true' : 'false' }}
                                },
                            @endforeach
                        ],
                        paging: true,
                        searching: true,
                        ordering: true,
                        responsive: true,
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
                    });
                @else
                    $('#{{ $tableId }}').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true,
                        responsive: true
                    });
                @endif
            @endif
        });
    </script>
    @endpush
@endif
