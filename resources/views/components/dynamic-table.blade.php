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
    'tableClass' => 'table table-striped align-items-center mb-0',
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
                // Persist and restore active tab per table instance
                (function() {
                    var tabsId = '#{{ $tableId }}Tabs';
                    var contentId = '#{{ $tableId }}TabsContent';
                    var storageKey = '{{ $tableId }}_active_tab';
                    var savedTab = localStorage.getItem(storageKey);

                    if (savedTab && $(tabsId + ' a[href="#' + savedTab + '"]').length) {
                        $(tabsId + ' a[href="#' + savedTab + '"]').tab('show');
                    }

                    $(tabsId + ' a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                        var targetId = $(e.target).attr('href'); // like #users
                        if (targetId) {
                            localStorage.setItem(storageKey, targetId.substring(1));
                        }
                    });
                })();
                @foreach($tabs as $key => $tab)
                    @if($enableAjaxPagination && isset($tab['ajaxUrl']))
                        const tableEl{{ $key }} = $('#{{ $tableId }}-{{ $key }}');
                        const overlayEl{{ $key }} = $('#{{ $tableId }}-{{ $key }}-overlay');
                        const colsSig{{ $key }} = '{{ implode(',', collect($tab['columns'])->pluck('key')->toArray()) }}';
                        const stateKey{{ $key }} = '{{ $tableId }}_{{ $key }}_state_' + colsSig{{ $key }};
                        const dt{{ $key }} = tableEl{{ $key }}.DataTable({
                            processing: false,
                            serverSide: true,
                            autoWidth: false,
                            stateSave: true,
                            stateDuration: 60 * 60 * 24, // 24 hours
                            stateLoadCallback: function (settings) {
                                var state = localStorage.getItem(stateKey{{ $key }});
                                return state ? JSON.parse(state) : null;
                            },
                            stateSaveCallback: function (settings, data) {
                                localStorage.setItem(stateKey{{ $key }}, JSON.stringify(data));
                            },
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
                            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                            preDrawCallback: function() { overlayEl{{ $key }}.removeClass('d-none'); },
                            drawCallback: function() {
                                overlayEl{{ $key }}.addClass('d-none');
                                dt{{ $key }}.columns.adjust();
                            }
                        });
                        // Custom debounce for global search
                        var searchTimer{{ $key }} = null;
                        $('#{{ $tableId }}-{{ $key }}_filter input').off('keyup.DT input.DT').on('keyup input', function(e) {
                            var searchValue = this.value;
                            clearTimeout(searchTimer{{ $key }});
                            searchTimer{{ $key }} = setTimeout(function() {
                                dt{{ $key }}.search(searchValue).draw();
                            }, 500);
                        });
                        // Adjust columns on tab show
                        $('a[data-toggle="tab"][href="#{{ $key }}"]').on('shown.bs.tab', function () {
                            dt{{ $key }}.columns.adjust();
                        });
                    @else
                        if ('{{ $key }}' !== 'permissions') {
                            $('#{{ $tableId }}-{{ $key }}').DataTable({
                                paging: true,
                                searching: true,
                                ordering: true,
                                responsive: true
                            });
                        }
                    @endif
                @endforeach
            @else
                @if($enableAjaxPagination && $ajaxUrl)
                        const tableEl = $('#{{ $tableId }}');
                        const overlayEl = $('#{{ $tableId }}-overlay');
                        const colsSig = '{{ implode(',', collect($columns)->pluck('key')->toArray()) }}';
                        const stateKey = '{{ $tableId }}_state_' + colsSig;
                        const dt = tableEl.DataTable({
                            processing: false,
                            serverSide: true,
                            autoWidth: false,
                            stateSave: true,
                            stateDuration: 60 * 60 * 24, // 24 hours
                            stateLoadCallback: function (settings) {
                                var state = localStorage.getItem(stateKey);
                                return state ? JSON.parse(state) : null;
                            },
                            stateSaveCallback: function (settings, data) {
                                localStorage.setItem(stateKey, JSON.stringify(data));
                            },
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
                            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                            preDrawCallback: function() { overlayEl.removeClass('d-none'); },
                            drawCallback: function() { overlayEl.addClass('d-none'); dt.columns.adjust(); }
                        });
                        // Custom debounce for global search
                        var searchTimer = null;
                        $('#{{ $tableId }}_filter input').off('keyup.DT input.DT').on('keyup input', function(e) {
                            var searchValue = this.value;
                            clearTimeout(searchTimer);
                            searchTimer = setTimeout(function() {
                                dt.search(searchValue).draw();
                            }, 500);
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
