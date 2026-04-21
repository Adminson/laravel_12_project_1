@props(['auditId', 'auditType', 'auditTitle' => 'Audit Log', 'collapseId' => 'accordionCustomThree'])

<div id="audit_logs_div">
    <div class="table-responsive">
        <table
            id="audit_logs_table"
            class="table table-bordered table-striped w-100"
            data-audit-url="{{ route('setting.audit.audit-list', [$auditId, $auditType]) }}"
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

@push('scripts')
    <script>
        let auditLogTable = null;
        let auditLogLoaded = false;

        function initAuditLogs() {
            const $table = $('#audit_logs_table');
            const url = $table.data('audit-url');

            if (!url) {
                return;
            }

            if ($.fn.DataTable.isDataTable('#audit_logs_table')) {
                $('#audit_logs_table').DataTable().destroy();
                $('#audit_logs_table tbody').empty();
            }

            auditLogTable = $table.DataTable({
                destroy: true,
                retrieve: false,
                responsive: false,
                autoWidth: false,
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
                        name: 'created_date',
                        width: '220px',
                        className: 'text-nowrap',
                        render: function(data, type, row) {
                            if (type === 'sort' || type === 'type') {
                                return row.created_date_sort ?? 0;
                            }

                            return data;
                        }
                    },
                    {
                        data: 'event',
                        name: 'event',
                        width: '140px',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'staff_name',
                        name: 'staff_name',
                        width: '220px',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'old_values',
                        name: 'old_values',
                        defaultContent: ''
                    },
                    {
                        data: 'new_values',
                        name: 'new_values',
                        defaultContent: ''
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
                        targets: [3, 4],
                        className: 'align-top'
                    },
                    {
                        targets: '_all',
                        defaultContent: ''
                    }
                ],
                createdRow: function(row, data) {
                    $('td:eq(1)', row).html(data.event);
                    $('td:eq(3)', row).html(data.old_values);
                    $('td:eq(4)', row).html(data.new_values);
                },
                initComplete: function() {
                    this.api().columns.adjust().draw(false);
                },
                language: {
                    emptyTable: 'No audit logs available',
                }
            });

            auditLogLoaded = true;
        }

        $(document).ready(function() {
            const auditCollapseEl = document.getElementById('{{ $collapseId }}');

            if (!auditCollapseEl) {
                return;
            }

            auditCollapseEl.addEventListener('shown.bs.collapse', function() {
                if (!auditLogLoaded) {
                    initAuditLogs();
                } else if (auditLogTable) {
                    auditLogTable.columns.adjust().draw(false);
                }
            });
        });
    </script>
@endpush
