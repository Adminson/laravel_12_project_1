<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">{{ $isEdit ? 'Edit' : 'Create' }} System Message</h4>
    </div>
    <div>
        <x-ui.link
            :href="route('setting.system_message.index', $company->cmp_id)"
            variant="secondary"
            class="me-1"
        >
            {{ $isEdit ? 'Back' : 'Cancel' }}
        </x-ui.link>
        <x-ui.button
            type="submit"
            variant="primary"
        >
            {{ $isEdit ? 'Update' : 'Save' }}
        </x-ui.button>
    </div>
</div>
@if ($formattedAlertMessages->isNotEmpty())
    <div class="mt-4">
        @foreach ($formattedAlertMessages as $alertMessage)
            <strong class="text-danger">{{ $alertMessage['title'] }}</strong>
            <div
                class="alert {{ $alertMessage['style_class'] }} d-flex align-items-center mb-3"
                role="alert"
            >
                <span class="alert-icon rounded">
                    <i class="icon-base ti {{ $alertMessage['style_icon'] }} icon-md"></i>
                </span>

                <div>

                    <div>{!! $alertMessage['formatted_description'] !!}</div>
                </div>
            </div>
            <div class="small text-muted mt-1">
                Show From: {{ $alertMessage['show_from_text'] }}
                <br>
                Show Until: {{ $alertMessage['show_until_text'] }}
            </div>
            <br />
        @endforeach
    </div>
@endif
<div class="row">
    <div class="col-md-6">
        <x-card.card
            title="Message"
            class="mt-0"
        >
            <div class="row">
                <div class="col-md-12 mb-3">
                    <x-form.input-label
                        for="msg_title"
                        value="Title"
                        :required="true"
                    />

                    <x-form.input-text
                        name="msg_title"
                        id="msg_title"
                        :value="$systemMessage->msg_title ?? ''"
                        :required="true"
                    />
                </div>

                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="msg_color"
                        value="Alert Message Type"
                        :required="true"
                    />

                    <x-form.input-select
                        name="msg_color"
                        id="msg_color"
                        :options="[
                            'blue' => 'Message (Blue)',
                            'orange' => 'Alert (Orange)',
                            'red' => 'Warning (Red)',
                        ]"
                        :value="$systemMessage->msg_color ?? 'blue'"
                        :required="true"
                    />
                </div>

                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="msg_date_reference"
                        value="Date Reference"
                        :required="true"
                    />

                    <x-form.input-select
                        name="msg_date_reference"
                        id="msg_date_reference"
                        :options="[
                            'message_date' => 'Message Date',
                            'subscribe_date' => 'Subscribe Date',
                        ]"
                        :value="old('msg_date_reference', $systemMessage->msg_date_reference ?? 'message_date')"
                        :required="true"
                    />
                </div>


                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="msg_suspend_login"
                        value="Suspend Login"
                        :required="true"
                    />

                    <x-form.input-select
                        name="msg_suspend_login"
                        id="msg_suspend_login"
                        :options="[
                            0 => 'No',
                            1 => 'Yes',
                        ]"
                        :value="(int) old('msg_suspend_login', $systemMessage->msg_suspend_login ?? 0)"
                        :required="true"
                    />
                </div>

                <div class="col-md-12 mb-3">
                    <x-form.input-label
                        for="msg_description"
                        value="Description, special command: [date1], [date2], [program]"
                        :required="true"
                    />

                    <x-form.input-quill
                        name="msg_description"
                        id="msg_description"
                        :value="$systemMessage->msg_description ?? ''"
                        placeholder="Write system message..."
                        :required="true"
                    />
                </div>
            </div>
        </x-card.card>
    </div>

    <div class="col-md-6">
        <x-card.card
            title="Timing"
            class="mt-0"
        >
            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="msg_start_day"
                        value="Start"
                        :required="true"
                    />

                    <x-form.input-text-group
                        name="msg_start_day"
                        id="msg_start_day"
                        type="number"
                        :value="$systemMessage->msg_start_day ?? ''"
                        min="0"
                        :required="true"
                        secondText="days"
                    />
                </div>

                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="msg_before_after"
                        value="Before / After"
                        :required="true"
                    />

                    <x-form.input-select
                        name="msg_before_after"
                        id="msg_before_after"
                        :options="[
                            'before' => 'Before',
                            'after' => 'After',
                        ]"
                        :value="old('msg_before_after', $systemMessage->msg_before_after ?? 'before')"
                        :required="true"
                    />
                </div>

                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="msg_date_type"
                        value="Date Type"
                        :required="true"
                    />

                    <x-form.input-select
                        name="msg_date_type"
                        id="msg_date_type"
                        :options="[
                            'date1' => 'Date 1',
                            'date2' => 'Date 2',
                        ]"
                        :value="old('msg_date_type', $systemMessage->msg_date_type ?? 'date1')"
                        :required="true"
                    />
                </div>

                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="msg_term"
                        value="Term"
                        :required="true"
                    />
                    <x-form.input-text-group
                        name="msg_term"
                        id="msg_term"
                        type="number"
                        :value="$systemMessage->msg_term ?? 0"
                        min="0"
                        step="1"
                        :required="true"
                        secondText="days"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form.input-label
                        for="msg_start_date"
                        value="Message Date 1"
                    />

                    <x-form.input-text
                        name="msg_start_date"
                        id="msg_start_date"
                        type="datetime-local"
                        :value="optional($systemMessage->msg_start_date)->format('Y-m-d\TH:i')"
                    />
                </div>

                <div class="col-md-6 mb-3">
                    <x-form.input-label
                        for="msg_end_date"
                        value="Message Date 2"
                    />

                    <x-form.input-text
                        name="msg_end_date"
                        id="msg_end_date"
                        type="datetime-local"
                        :value="optional($systemMessage->msg_end_date)->format('Y-m-d\TH:i')"
                    />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form.input-label
                        for="cmp_sub_start_date"
                        value="Subscribe Start Date"
                    />

                    <x-form.input-text
                        name="cmp_sub_start_date"
                        id="cmp_sub_start_date"
                        type="datetime-local"
                        :value="optional($company->cmp_sub_start_date)->format('Y-m-d\TH:i')"
                        disabled
                    />
                </div>

                <div class="col-md-6 mb-3">
                    <x-form.input-label
                        for="cmp_sub_end_date"
                        value="Subscribe End Date"
                    />

                    <x-form.input-text
                        name="cmp_sub_end_date"
                        id="cmp_sub_end_date"
                        type="datetime-local"
                        :value="optional($company->cmp_sub_end_date)->format('Y-m-d\TH:i')"
                        disabled
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div
                        id="date_reference_preview"
                        class="alert alert-outline-dark border small mb-0"
                    >
                        <div class="fw-semibold mb-2 text-primary">Timing Preview</div>

                        <div class="row g-2 small">
                            <div class="col-md-6">
                                <div>
                                    <strong>Selected reference:</strong>
                                    <span id="preview_reference_label">-</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div>
                                    <strong>Reference datetime:</strong>
                                    <span id="preview_reference_value">-</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div>
                                    <strong>Show from:</strong>
                                    <span id="preview_show_from">-</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div>
                                    <strong>Show until:</strong>
                                    <span id="preview_show_until">-</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div>
                                    <strong>Rule:</strong>
                                    <span id="preview_formula">-</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div
                                    id="preview_warning"
                                    class="text-danger fw-semibold"
                                    style="display:none;"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-card.card>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <x-card.card
            title="Email"
            class="mt-0"
        >
            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-form.checkbox
                        name="msg_enable_email"
                        id="msg_enable_email"
                        label="Enable Email"
                        :checked="old('msg_enable_email', $systemMessage->msg_enable_email ?? false)"
                    />
                </div>

                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="msg_last_date_sent_email"
                        value="Last Sent Email"
                    />

                    <x-form.input-text
                        name="msg_last_date_sent_email"
                        id="msg_last_date_sent_email"
                        :value="optional($systemMessage->msg_last_date_sent_email)->format('d-M-Y h:i:s A')"
                        readonly
                    />
                    {{-- show msg_last_date_sent_email when last email sent as readonly --}}
                </div>

                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="resend_email_button"
                        value="Resend Email"
                    />
                    <br />
                    <button
                        id="resend_email_button"
                        type="button"
                        class="btn btn-primary"
                        data-url="{{ $isEdit ? route('setting.system_message.resend_email', ['company_profile' => $company->cmp_id, 'system_message' => $systemMessage->msg_id]) : '' }}"
                        @disabled(!$isEdit)
                    >
                        Send Email
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <x-form.input-label
                        for="msg_email_date"
                        value="Email Days (separate days by comma, ex: -90,-60,-30,-7,-3,-2,-1,0,1,2,3)"
                    />

                    <x-form.input-text
                        name="msg_email_date"
                        id="msg_email_date"
                        :value="$systemMessage->msg_email_date ?? ''"
                    />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <x-form.input-label
                        for="msg_email"
                        value="Email"
                    />

                    <x-form.input-text
                        name="msg_email"
                        id="msg_email"
                        :value="!empty($systemMessage->msg_email) ? implode(';', $systemMessage->msg_email) : ''"
                        placeholder="aaa@gmail.com;bbb@gmail.com;ccc@gmail.com"
                    />

                    <small class="text-muted">
                        Separate multiple emails with semicolon (;)
                    </small>
                </div>
            </div>

            <div class="mt-3">
                <div
                    id="email_schedule_preview"
                    class="alert alert-outline-dark border small mb-0"
                >
                    <div class="fw-semibold mb-2 text-primary">Email Schedule Preview</div>
                    <div
                        id="email_schedule_preview_content"
                        class="small text-muted"
                    >
                        No email schedule calculated yet.
                    </div>
                </div>
            </div>
        </x-card.card>
    </div>
</div>

@if ($isEdit)
    {{-- System section include memo + system info + audit  --}}
    <h5 class="mt-4 mb-0">System Info</h5>
    <div class="row">
        <div class="col-12">
            <div
                class="accordion accordion-custom-button mt-3"
                id="accordionSystem"
            >
                {{-- Eamil Logs --}}
                <div class="accordion-item">
                    <h2
                        class="accordion-header"
                        id="headingEmailLog"
                    >
                        <button
                            type="button"
                            class="accordion-button collapsed"
                            data-bs-toggle="collapse"
                            data-bs-target="#accordionEmailLog"
                            aria-expanded="false"
                            aria-controls="accordionEmailLog"
                        >
                            Email Log
                        </button>
                    </h2>

                    <div
                        id="accordionEmailLog"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingEmailLog"
                        data-bs-parent="#accordionSystem"
                    >
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table
                                    id="system-message-email-log-table"
                                    class="table table-bordered table-striped w-100"
                                >
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">No</th>
                                            <th>Recipient Email</th>
                                            <th style="width: 120px;">Offset Day</th>
                                            <th style="width: 180px;">Scheduled For</th>
                                            <th style="width: 120px;">Trigger Type</th>
                                            <th style="width: 120px;">Status</th>
                                            <th style="width: 180px;">Sent At</th>
                                            <th>Error Message</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Memo / Notes --}}
                <div class="accordion-item">
                    <h2
                        class="accordion-header"
                        id="headingMemo"
                    >
                        <button
                            type="button"
                            class="accordion-button collapsed"
                            data-bs-toggle="collapse"
                            data-bs-target="#accordionSystemOne"
                            aria-expanded="false"
                            aria-controls="accordionSystemOne"
                        >
                            Memo / Notes
                        </button>
                    </h2>

                    <div
                        id="accordionSystemOne"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingMemo"
                        data-bs-parent="#accordionSystem"
                    >
                        <div class="accordion-body">
                            @include('admin.memo._memo-panel_accordion', [
                                'title' => 'Memo / Notes',
                                'memoableType' => 'system_message',
                                'memoableId' => $systemMessage->msg_id,
                                'memos' => $systemMessage->memos,
                            ])
                        </div>
                    </div>
                </div>

                {{-- System Logs --}}
                <div class="accordion-item">
                    <h2
                        class="accordion-header"
                        id="headingSystemLog"
                    >
                        <button
                            type="button"
                            class="accordion-button collapsed"
                            data-bs-toggle="collapse"
                            data-bs-target="#accordionSystemTwo"
                            aria-expanded="false"
                            aria-controls="accordionSystemTwo"
                        >
                            System Logs
                        </button>
                    </h2>

                    <div
                        id="accordionSystemTwo"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingSystemLog"
                        data-bs-parent="#accordionSystem"
                    >
                        <div class="accordion-body">
                            @include('admin.system.show_accordion', [
                                'record' => $systemMessage,
                                'prefix' => 'msg',
                                'fields' => [
                                    ['suffix' => 'id', 'col' => 'col-md-3'],
                                    ['suffix' => 'version', 'col' => 'col-md-3'],
                                    ['suffix' => 'hit', 'col' => 'col-md-3'],
                                    ['suffix' => 'viewedby', 'col' => 'col-md-3'],
                                    ['suffix' => 'createdon', 'col' => 'col-md-6', 'type' => 'datetime'],
                                    ['suffix' => 'createdby', 'col' => 'col-md-6'],
                                    ['suffix' => 'modifiedon', 'col' => 'col-md-6', 'type' => 'datetime'],
                                    ['suffix' => 'modifiedby', 'col' => 'col-md-6'],
                                ],
                            ])
                        </div>
                    </div>
                </div>

                {{-- Audit Logs --}}
                <div class="accordion-item">
                    <h2
                        class="accordion-header"
                        id="headingCustomThree"
                    >
                        <button
                            type="button"
                            class="accordion-button collapsed"
                            data-bs-toggle="collapse"
                            data-bs-target="#accordionAuditLog"
                            aria-expanded="false"
                            aria-controls="accordionAuditLog"
                        >
                            Audit Logs
                        </button>
                    </h2>

                    <div
                        id="accordionAuditLog"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingCustomThree"
                        data-bs-parent="#accordionSystem"
                    >
                        <div class="accordion-body">
                            @include('admin.audit.show_accordion', [
                                'auditId' => $systemMessage->msg_id,
                                'auditType' => 'system_message',
                                'auditTitle' => 'Audit Log',
                                'collapseId' => 'accordionAuditLog',
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        function toggleEmailInput() {
            const enableEmail = $('#msg_enable_email').is(':checked');

            $('#msg_email').prop('readonly', !enableEmail);
            $('#msg_email_date').prop('readonly', !enableEmail);
        }

        function getSelectedReferenceConfig() {
            const dateReference = $('#msg_date_reference').val();
            const dateType = $('#msg_date_type').val();

            if (dateReference === 'subscribe_date' && dateType === 'date1') {
                return {
                    label: 'Subscribe Start Date',
                    selector: '#cmp_sub_start_date'
                };
            }

            if (dateReference === 'subscribe_date' && dateType === 'date2') {
                return {
                    label: 'Subscribe End Date',
                    selector: '#cmp_sub_end_date'
                };
            }

            if (dateReference === 'message_date' && dateType === 'date1') {
                return {
                    label: 'Message Date 1',
                    selector: '#msg_start_date'
                };
            }

            if (dateReference === 'message_date' && dateType === 'date2') {
                return {
                    label: 'Message Date 2',
                    selector: '#msg_end_date'
                };
            }

            return {
                label: '-',
                selector: null
            };
        }

        function parseDateTimeLocal(value) {
            if (!value) {
                return null;
            }

            const date = new Date(value);

            if (isNaN(date.getTime())) {
                return null;
            }

            return date;
        }

        function addDays(date, days) {
            const result = new Date(date.getTime());
            result.setDate(result.getDate() + days);
            return result;
        }

        function formatDateTime(date) {
            if (!date) {
                return '-';
            }

            const pad = (num) => String(num).padStart(2, '0');

            const day = pad(date.getDate());
            const month = pad(date.getMonth() + 1);
            const year = date.getFullYear();

            let hours = date.getHours();
            const minutes = pad(date.getMinutes());
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12;

            return `${day}/${month}/${year} ${pad(hours)}:${minutes} ${ampm}`;
        }

        function updateDateReferencePreview() {
            const timing = getTimingCalculation();

            $('#preview_reference_label').text(timing.config.label || '-');
            $('#preview_reference_value').text(
                timing.referenceDate ? formatDateTime(timing.referenceDate) : '-'
            );
            $('#preview_show_from').text(
                timing.showFrom ? formatDateTime(timing.showFrom) : '-'
            );
            $('#preview_show_until').text(
                timing.showUntil ?
                formatDateTime(timing.showUntil) :
                (timing.showFrom ? 'Until deleted' : '-')
            );
            $('#preview_formula').text(timing.formulaText);

            if (timing.warningText) {
                $('#preview_warning').text(timing.warningText).show();
            } else {
                $('#preview_warning').hide().text('');
            }
        }

        function updateEmailSchedulePreview() {
            const enableEmail = $('#msg_enable_email').is(':checked');
            const timing = getTimingCalculation();
            const offsets = parseEmailOffsets($('#msg_email_date').val());

            if (!enableEmail) {
                $('#email_schedule_preview_content').html(
                    '<span class="text-muted">Email notification is disabled.</span>'
                );
                return;
            }

            if (!timing.showFrom) {
                $('#email_schedule_preview_content').html(
                    '<span class="text-danger">Show From date is not ready yet. Complete timing fields first.</span>'
                );
                return;
            }

            if (offsets.length === 0) {
                $('#email_schedule_preview_content').html(
                    '<span class="text-muted">No valid email offsets found. Example: -10,-5,0,1,5,10</span>'
                );
                return;
            }

            let rows = `
        <div class="mb-2">
            <strong>Base Date:</strong> Show From = ${formatDateTime(timing.showFrom)}
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="width:120px;">Offset Day</th>
                        <th>Send On</th>
                    </tr>
                </thead>
                <tbody>
    `;

            offsets.forEach(offset => {
                const sendDate = addDays(timing.showFrom, offset);

                rows += `
            <tr>
                <td>${offset > 0 ? '+' + offset : offset}</td>
                <td>${formatDateTime(sendDate)}</td>
            </tr>
        `;
            });

            rows += `
                    </tbody>
                </table>
            </div>
        `;

            $('#email_schedule_preview_content').html(rows);
        }

        $(function() {
            toggleEmailInput();
            updateDateReferencePreview();
            updateEmailSchedulePreview();

            $('#msg_enable_email').on('change', function() {
                toggleEmailInput();
                updateEmailSchedulePreview();
            });

            $(
                '#msg_date_reference, ' +
                '#msg_date_type, ' +
                '#msg_before_after, ' +
                '#msg_start_day, ' +
                '#msg_term, ' +
                '#msg_start_date, ' +
                '#msg_end_date, ' +
                '#cmp_sub_start_date, ' +
                '#cmp_sub_end_date, ' +
                '#msg_email_date'
            ).on('change input keyup', function() {
                updateDateReferencePreview();
                updateEmailSchedulePreview();
            });

            if (document.getElementById('msg_description_editor')) {
                const messageQuill = new Quill('#msg_description_editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{
                                header: [1, 2, 3, 4, 5, 6, false]
                            }],
                            [{
                                font: []
                            }],
                            [{
                                size: ['small', false, 'large', 'huge']
                            }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{
                                color: []
                            }, {
                                background: []
                            }],
                            [{
                                script: 'sub'
                            }, {
                                script: 'super'
                            }],
                            [{
                                list: 'ordered'
                            }, {
                                list: 'bullet'
                            }],
                            [{
                                indent: '-1'
                            }, {
                                indent: '+1'
                            }],
                            [{
                                align: []
                            }],
                            ['blockquote', 'code-block'],
                            ['link', 'image', 'video', 'formula'],
                            ['clean']
                        ]
                    }
                });

                messageQuill.on('text-change', function() {
                    $('#msg_description').val(messageQuill.root.innerHTML);
                });
            }
        });

        function getTimingCalculation() {
            const config = getSelectedReferenceConfig();

            const startDay = parseInt($('#msg_start_day').val(), 10) || 0;
            const beforeAfter = ($('#msg_before_after').val() || 'before').toLowerCase();
            const term = parseInt($('#msg_term').val(), 10) || 0;

            const referenceRawValue = config.selector ? $(config.selector).val() : '';
            const referenceDate = parseDateTimeLocal(referenceRawValue);

            let showFrom = null;
            let showUntil = null;
            let formulaText = '-';
            let warningText = '';

            if (!config.selector) {
                warningText = 'Unable to determine selected reference date.';
            } else if (!referenceDate) {
                warningText = `${config.label} is empty. Please fill in the selected reference datetime first.`;
            } else {
                showFrom = beforeAfter === 'after' ?
                    addDays(referenceDate, startDay) :
                    addDays(referenceDate, -startDay);

                if (term > 0) {
                    showUntil = addDays(showFrom, term);
                }

                formulaText =
                    `Reference "${config.label}" ` +
                    `${beforeAfter === 'after' ? '+' : '-'} ${startDay} day(s)` +
                    `${term > 0 ? `, then + ${term} day(s) term` : ', term = 0 (until deleted)'}`;
            }

            return {
                config,
                referenceDate,
                showFrom,
                showUntil,
                formulaText,
                warningText
            };
        }

        function parseEmailOffsets(rawValue) {
            if (!rawValue) {
                return [];
            }

            const values = rawValue.split(',')
                .map(value => value.trim())
                .filter(value => /^-?\d+$/.test(value))
                .map(value => parseInt(value, 10));

            return [...new Set(values)].sort((a, b) => a - b);
        }

        $('#resend_email_button').on('click', function() {
            const url = $(this).data('url');

            if (!url) {
                alert('Please save the system message first before manual resend.');
                return;
            }

            const button = $(this);
            button.prop('disabled', true).text('Sending...');

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message || 'Email queued successfully.');
                    location.reload();
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Failed to queue email.';
                    alert(message);
                },
                complete: function() {
                    button.prop('disabled', false).text('Send Email');
                }
            });
        });

        @if ($isEdit)
            $('#system-message-email-log-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('setting.system_message.email_log_list', [
                    'company_profile' => $company->cmp_id,
                    'system_message' => $systemMessage->msg_id,
                ]) }}',
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'recipient_email',
                        name: 'recipient_email'
                    },
                    {
                        data: 'scheduled_offset_day',
                        name: 'scheduled_offset_day'
                    },
                    {
                        data: 'scheduled_for',
                        name: 'scheduled_for'
                    },
                    {
                        data: 'trigger_type',
                        name: 'trigger_type'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'sent_at',
                        name: 'sent_at'
                    },
                    {
                        data: 'error_message',
                        name: 'error_message',
                        orderable: false
                    }
                ]
            });
        @endif
    </script>
@endpush
