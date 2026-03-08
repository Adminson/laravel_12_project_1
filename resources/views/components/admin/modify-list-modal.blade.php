{{-- resources/views/components/admin/modify-list-modal.blade.php --}}
@once
    @push('scripts')
        @vite('resources/js/select-input-list-editor.js')
    @endpush

    <div class="modal fade" id="selectDynamicInput_modifyListModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Modify List: <span id="selectDynamicInput_modalDataTypeLabel"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label">Current Choices</label>
                            <div class="list-group" id="selectDynamicInput_currentChoices" style="max-height: 320px; overflow:auto;"></div>
                            <div class="form-text">Click an item to edit / delete.</div>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Add / Edit Choice</label>
                            <input type="text" class="form-control" id="selectDynamicInput_choiceInput" placeholder="e.g. Sunday" maxlength="255">

                            <div class="mt-3 d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-outline-secondary" id="selectDynamicInput_btnNew">New</button>
                                <button type="button" class="btn btn-primary" id="selectDynamicInput_btnAdd">Add</button>
                                <button type="button" class="btn btn-success" id="selectDynamicInput_btnSave" disabled>Save</button>
                                <button type="button" class="btn btn-danger" id="selectDynamicInput_btnDelete" disabled>Delete</button>
                            </div>

                            <div class="alert alert-danger mt-3 d-none" id="selectDynamicInput_choiceError"></div>
                            <div class="alert alert-success mt-3 d-none" id="selectDynamicInput_choiceSuccess"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endonce
