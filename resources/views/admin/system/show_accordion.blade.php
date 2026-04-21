@php
    use Illuminate\Support\Carbon;

    $title = $title ?? 'System Info';
    $record = $record ?? null;
    $prefix = $prefix ?? null;
    $fallback = $fallback ?? '-';

    $fields = $fields ?? [
        ['suffix' => 'id', 'col' => 'col-md-3'],
        ['suffix' => 'version', 'col' => 'col-md-3'],
        ['suffix' => 'hit', 'col' => 'col-md-3'],
        ['suffix' => 'viewedby', 'col' => 'col-md-3'],
        ['suffix' => 'createdon', 'col' => 'col-md-6', 'type' => 'datetime'],
        ['suffix' => 'createdby', 'col' => 'col-md-6'],
        ['suffix' => 'modifiedon', 'col' => 'col-md-6', 'type' => 'datetime'],
        ['suffix' => 'modifiedby', 'col' => 'col-md-6'],
    ];
@endphp

@if ($record && $prefix)
    <div class="row">
        @foreach ($fields as $field)
            @php
                $suffix = $field['suffix'] ?? null;
                $column = $field['column'] ?? ($suffix ? $prefix . '_' . $suffix : null);
                $label = $field['label'] ?? $column;
                $name = $field['name'] ?? 'view_' . $column;
                $col = $field['col'] ?? 'col-md-6';
                $type = $field['type'] ?? 'text';

                $rawValue = $column ? data_get($record, $column) : null;

                if ($type === 'datetime') {
                    $value = filled($rawValue) ? Carbon::parse($rawValue)->format('Y-m-d H:i:s') : $fallback;
                } else {
                    $value = filled($rawValue) ? $rawValue : $fallback;
                }
            @endphp

            <div class="{{ $col }} mb-3">
                <x-form.input-label :value="$label" />

                <x-form.input-text
                    :name="$name"
                    :value="$value"
                    readonly
                />
            </div>
        @endforeach
    </div>
@endif
