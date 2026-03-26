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

                <div class="col-md-6 mb-3">
                    <x-form.input-label
                        for="msg_type"
                        value="Message/Alert Type"
                        :required="true"
                    />

                    <x-form.input-select
                        name="msg_type"
                        id="msg_type"
                        :options="[
                            'blue' => 'Blue',
                            'orange' => 'Orange',
                            'red' => 'Red',
                        ]"
                        :value="$systemMessage->msg_type ?? 'blue'"
                        :required="true"
                    />
                </div>

                <div class="col-md-6 mb-3">
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
                        :value="$systemMessage->msg_before_after ?? 'before'"
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
                        :value="$systemMessage->msg_date_type ?? 'date1'"
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
                <div class="col-md-6 mb-3">
                    <x-form.checkbox
                        name="msg_enable_email"
                        id="msg_enable_email"
                        label="Enable Email"
                        :checked="old('msg_enable_email', $systemMessage->msg_enable_email ?? false)"
                    />
                </div>

                <div class="col-md-6 mb-3">
                    <x-form.input-label
                        for="msg_last_date_sent_email"
                        value="Last Sent Email"
                    />

                    <x-form.input-text
                        name="msg_last_date_sent_email"
                        id="msg_last_date_sent_email"
                        type="datetime-local"
                        :value="optional($systemMessage->msg_last_date_sent_email)->format('Y-m-d\TH:i')"
                    />
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
        </x-card.card>
    </div>

    <div class="col-md-6">
        <x-card.card
            title="Audit"
            class="mt-0"
        >
            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="msg_version"
                        value="Version"
                    />

                    <x-form.input-text
                        name="msg_version"
                        id="msg_version"
                        :value="$systemMessage->msg_version ?? 1"
                        disabled
                    />
                </div>

                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="msg_hit"
                        value="Hit"
                    />

                    <x-form.input-text
                        name="msg_hit"
                        id="msg_hit"
                        :value="$systemMessage->msg_hit ?? 0"
                        disabled
                    />
                </div>

                <div class="col-md-4 mb-3">
                    <x-form.input-label
                        for="msg_viewedby"
                        value="Viewed By"
                    />

                    <x-form.input-text
                        name="msg_viewedby"
                        id="msg_viewedby"
                        :value="$systemMessage->msg_viewedby ?? ''"
                        disabled
                    />
                </div>

                <div class="col-md-6 mb-3">
                    <x-form.input-label
                        for="msg_createdon"
                        value="Created On"
                    />

                    <x-form.input-text
                        name="msg_createdon"
                        id="msg_createdon"
                        :value="optional($systemMessage->msg_createdon)->format('d-M-Y H:i:s')"
                        disabled
                    />
                </div>

                <div class="col-md-6 mb-3">
                    <x-form.input-label
                        for="msg_modifiedon"
                        value="Modified On"
                    />

                    <x-form.input-text
                        name="msg_modifiedon"
                        id="msg_modifiedon"
                        :value="optional($systemMessage->msg_modifiedon)->format('d-M-Y H:i:s')"
                        disabled
                    />
                </div>

                <div class="col-md-6 mb-3">
                    <x-form.input-label
                        for="msg_viewedon"
                        value="Viewed On"
                    />

                    <x-form.input-text
                        name="msg_viewedon"
                        id="msg_viewedon"
                        :value="optional($systemMessage->msg_viewedon)->format('d-M-Y H:i:s')"
                        disabled
                    />
                </div>

                <div class="col-md-6 mb-3">
                    <x-form.input-label
                        for="msg_createdby"
                        value="Created By"
                    />

                    <x-form.input-text
                        name="msg_createdby"
                        id="msg_createdby"
                        :value="$systemMessage->msg_createdby ?? ''"
                        disabled
                    />
                </div>

                <div class="col-md-6 mb-3">
                    <x-form.input-label
                        for="msg_modifiedby"
                        value="Modified By"
                    />

                    <x-form.input-text
                        name="msg_modifiedby"
                        id="msg_modifiedby"
                        :value="$systemMessage->msg_modifiedby ?? ''"
                        disabled
                    />
                </div>
            </div>
        </x-card.card>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-3">
    <x-ui.link
        :href="route('setting.system_message.index', $company->cmp_id)"
        variant="secondary"
    >
        Cancel
    </x-ui.link>

    <button
        type="submit"
        class="btn btn-primary"
    >
        {{ $isEdit ? 'Update' : 'Save' }}
    </button>
</div>

@push('scripts')
    <script>
        function toggleEmailInput() {
            const enableEmail = $('#msg_enable_email').is(':checked');

            $('#msg_email').prop('readonly', !enableEmail);
            $('#msg_email_date').prop('readonly', !enableEmail);

            if (!enableEmail) {
                // $('#msg_email').val('');
                // $('#msg_email_date').val('');
            }
        }

        $(function() {
            toggleEmailInput();

            $('#msg_enable_email').on('change', function() {
                toggleEmailInput();
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
    </script>
@endpush
