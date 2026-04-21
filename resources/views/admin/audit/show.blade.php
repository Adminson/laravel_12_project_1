@props(['auditId', 'auditType', 'auditTitle' => 'Audit Log'])

<div class="mt-4">
    <label class="form-label fw-semibold d-block mb-2">{{ $auditTitle }}</label>
    <x-ui.button
        type="button"
        variant="success"
        size="sm"
        id="audit_log_btn"
        {{-- icon="ti ti-eye" --}}
        data-audit-url="{{ route('setting.audit.audit-list', [$auditId, $auditType]) }}"
    >
        View Log
    </x-ui.button>
</div>

<div
    id="audit_logs_div"
    class="mt-3"
    style="display: none;"
>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table
                    id="audit_logs_table"
                    class="table table-bordered table-striped w-100"
                >
                    <thead>
                        <tr>
                            <th style="width: 180px;">Created Date</th>
                            <th style="width: 120px;">Event</th>
                            <th style="width: 180px;">Staff Name</th>
                            <th>Old Values</th>
                            <th>New Values</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        let auditLogTable = null;

        function loadAuditLogs(url) {
            const $wrapper = $('#audit_logs_div');
            const $table = $('#audit_logs_table');
            const $button = $('#audit_log_btn');

            if ($wrapper.is(':hidden')) {
                $wrapper.show();
                $button.html('<span>Hide Log</span>');
                // $button.html('<i class="ti ti-eye-off me-1"></i><span>Hide Log</span>');
            } else {
                $wrapper.hide();
                $button.html('<span>View Log</span>');
                // $button.html('<i class="ti ti-eye me-1"></i><span>View Log</span>');
                return;
            }

            if ($.fn.DataTable.isDataTable('#audit_logs_table')) {
                auditLogTable.ajax.url(url).load();
                return;
            }

            auditLogTable = $table.DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: url,
                    type: 'GET',
                    dataSrc: 'data',
                },
                pageLength: 10,
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'created_date',
                        name: 'created_date'
                    },
                    {
                        data: 'event',
                        name: 'event'
                    },
                    {
                        data: 'staff_name',
                        name: 'staff_name'
                    },
                    {
                        data: 'old_values',
                        name: 'old_values'
                    },
                    {
                        data: 'new_values',
                        name: 'new_values'
                    },
                ],
                columnDefs: [{
                        targets: [1, 3, 4],
                        orderable: false
                    },
                    {
                        targets: [1, 3, 4],
                        searchable: false
                    },
                    {
                        targets: [1, 3, 4],
                        className: 'align-top'
                    },
                ],
                createdRow: function(row, data) {
                    $('td:eq(1)', row).html(data.event);
                    $('td:eq(3)', row).html(data.old_values);
                    $('td:eq(4)', row).html(data.new_values);
                },
                language: {
                    emptyTable: 'No audit logs available',
                }
            });
        }

        $(document).ready(function() {
            $(document).on('click', '#audit_log_btn', function() {
                const url = $(this).data('audit-url');
                loadAuditLogs(url);
            });
        });
    </script>
@endpush
