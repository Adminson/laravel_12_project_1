@props(['auditId', 'auditType', 'auditTitle' => 'Audit Log'])

<div class="mt-4">
    <label class="form-label fw-semibold d-block mb-2">{{ $auditTitle }}</label>

    <button
        type="button"
        class="btn btn-success btn-sm"
        id="audit_log_btn"
        data-audit-url="{{ route('setting.audit.audit-list', [$auditId, $auditType]) }}"
    >
        <i class="ti ti-eye me-1"></i>
        <span>View Log</span>
    </button>
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
