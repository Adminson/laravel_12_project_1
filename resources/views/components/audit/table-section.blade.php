@props([
    'id' => 'auditLogSection',
    'tableId' => 'auditLogTable',
    'ajaxUrl',
    'title' => 'Audit Log',
])

<div
    id="{{ $id }}"
    class="mt-4 d-none"
>
    <x-card.section-card
        :title="$title"
        sub-title="Track all changes for this record"
    >
        <div class="table-responsive">
            <table
                id="{{ $tableId }}"
                class="table table-bordered table-striped w-100"
            >
                <thead>
                    <tr>
                        <th style="width: 180px;">Created Date</th>
                        <th style="width: 120px;">Event</th>
                        <th style="width: 150px;">Staff Name</th>
                        <th>Old Values</th>
                        <th>New Values</th>
                    </tr>
                </thead>
            </table>
        </div>

        <input
            type="hidden"
            id="{{ $tableId }}_ajax_url"
            value="{{ $ajaxUrl }}"
        >
    </x-card.section-card>
</div>
