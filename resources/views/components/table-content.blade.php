@props([
    'columns' => [],
    'data' => [],
    'actions' => [],
    'tableId' => 'dataTable',
    'tableClass' => 'table align-items-center mb-0',
    'emptyMessage' => 'No data found',
    'enableDataTable' => true
])

<div class="table-responsive p-0">
    <table id="{{ $tableId }}" class="{{ $tableClass }}">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th class="{{ $column['class'] ?? 'text-uppercase text-secondary text-xxs font-weight-bolder opacity-7' }}">
                        {{ $column['label'] }}
                    </th>
                @endforeach
                @if(!empty($actions))
                    <th class="text-secondary opacity-7 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Actions
                    </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    @foreach($columns as $column)
                        <td class="{{ $column['td_class'] ?? '' }}">
                            @if(isset($column['type']) && $column['type'] === 'custom')
                                {!! $column['render']($item) !!}
                            @elseif(isset($column['type']) && $column['type'] === 'date')
                                @if($item->{$column['field'] ?? $column['key']})
                                    {{ \Carbon\Carbon::parse($item->{$column['field'] ?? $column['key']})->format($column['format'] ?? 'Y-m-d H:i:s') }}
                                @else
                                    N/A
                                @endif
                            @elseif(isset($column['type']) && $column['type'] === 'boolean')
                                {{ $item->{$column['field'] ?? $column['key']} ? ($column['true_text'] ?? 'Yes') : ($column['false_text'] ?? 'No') }}
                            @elseif(isset($column['type']) && $column['type'] === 'relation')
                                {{ data_get($item, $column['field'] ?? $column['key']) ?? 'N/A' }}
                            @elseif(isset($column['type']) && $column['type'] === 'limit')
                                {{ Str::limit($item->{$column['field'] ?? $column['key']}, $column['limit'] ?? 50) }}
                            @elseif(isset($column['type']) && $column['type'] === 'badge')
                                <span class="badge badge-{{ $column['color']($item) ?? 'primary' }}">
                                    {{ $item->{$column['field'] ?? $column['key']} }}
                                </span>
                            @else
                                {{ $item->{$column['field'] ?? $column['key']} ?? 'N/A' }}
                            @endif
                        </td>
                    @endforeach
                    
                    @if(!empty($actions))
                        <td>
                            <div class="d-flex align-items-center">
                                @foreach($actions as $action)
                                    @if($action['type'] === 'link')
                                        <a href="{{ route($action['route'], $item->id) }}" 
                                           class="btn btn-sm {{ $action['class'] ?? 'btn-primary' }} {{ $action['spacing'] ?? 'me-2' }}"
                                           @if(isset($action['tooltip'])) data-toggle="tooltip" title="{{ $action['tooltip'] }}" @endif>
                                            @if(isset($action['icon']))
                                                <i class="{{ $action['icon'] }}"></i>
                                            @endif
                                            {{ $action['label'] ?? '' }}
                                        </a>
                                    @elseif($action['type'] === 'form')
                                        <form action="{{ route($action['route'], $item->id) }}" 
                                              method="POST" 
                                              style="display:inline-block;"
                                              @if(isset($action['confirm'])) onsubmit="return confirm('{{ $action['confirm'] }}')" @endif>
                                            @csrf
                                            @if(isset($action['method']))
                                                @method($action['method'])
                                            @endif
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $action['class'] ?? 'btn-danger' }}"
                                                    @if(isset($action['tooltip'])) data-toggle="tooltip" title="{{ $action['tooltip'] }}" @endif>
                                                @if(isset($action['icon']))
                                                    <i class="{{ $action['icon'] }}"></i>
                                                @endif
                                                {{ $action['label'] ?? '' }}
                                            </button>
                                        </form>
                                    @elseif($action['type'] === 'custom')
                                        {!! $action['render']($item) !!}
                                    @endif
                                @endforeach
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + (!empty($actions) ? 1 : 0) }}" class="text-center py-4">
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>