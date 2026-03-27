let auditLogTable = null;

function loadAuditLogs(url) {
    const $wrapper = $('#audit_logs_div');
    const $table = $('#audit_logs_table');
    const $button = $('#audit_log_btn');

    if ($wrapper.is(':hidden')) {
        $wrapper.show();
        $button.html('<i class="ti ti-eye-off me-1"></i><span>Hide Log</span>');
    } else {
        $wrapper.hide();
        $button.html('<i class="ti ti-eye me-1"></i><span>View Log</span>');
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
        order: [[0, 'desc']],
        columns: [
            { data: 'created_date', name: 'created_date' },
            { data: 'event', name: 'event' },
            { data: 'staff_name', name: 'staff_name' },
            { data: 'old_values', name: 'old_values' },
            { data: 'new_values', name: 'new_values' },
        ],
        columnDefs: [
            { targets: [1, 3, 4], orderable: false },
            { targets: [1, 3, 4], searchable: false },
            { targets: [1, 3, 4], className: 'align-top' },
        ],
        createdRow: function (row, data) {
            $('td:eq(1)', row).html(data.event);
            $('td:eq(3)', row).html(data.old_values);
            $('td:eq(4)', row).html(data.new_values);
        },
        language: {
            emptyTable: 'No audit logs available',
        }
    });
}

$(document).ready(function () {
    $(document).on('click', '#audit_log_btn', function () {
        const url = $(this).data('audit-url');
        loadAuditLogs(url);
    });
});