(function ($) {
    const MODIFY_VALUE = '__modify__';
    const PREFIX = 'selectDynamicInput_';

    // Helper: build "#id"
    const sid = (name) => `#${PREFIX}${name}`;

    const modalEl = document.getElementById(`${PREFIX}modifyListModal`);
    const modal = new bootstrap.Modal(modalEl);

    let activeSelect = null;
    let activeDataType = null;
    let selectedItemId = null;

    function showError(msg) {
        $(sid('choiceError')).removeClass('d-none').text(msg || 'Something went wrong.');
        $(sid('choiceSuccess')).addClass('d-none').text('');
    }

    function showSuccess(msg) {
        $(sid('choiceSuccess')).removeClass('d-none').text(msg || 'Saved.');
        $(sid('choiceError')).addClass('d-none').text('');
    }

    function clearAlerts() {
        $(sid('choiceError')).addClass('d-none').text('');
        $(sid('choiceSuccess')).addClass('d-none').text('');
    }

    function resetEditorSelection() {
        selectedItemId = null;
        $(sid('choiceInput')).val('');
        $(`${sid('btnSave')}, ${sid('btnDelete')}`).prop('disabled', true);
        $(`${sid('currentChoices')} .list-group-item`).removeClass('active');
    }

    function renderModalList(items) {
        const $box = $(sid('currentChoices'));
        $box.empty();

        if (!items || !items.length) {
            $box.append(`<div class="text-muted p-2">No items yet.</div>`);
            return;
        }

        items.forEach(item => {
            const safeText = $('<div/>').text(item.select_value).html();
            $box.append(`
                <button type="button" class="list-group-item list-group-item-action"
                        data-id="${item.id}" data-value="${safeText}">
                    ${safeText}
                </button>
            `);
        });
    }

    function renderSelectOptions($select, items, keepSelectedValue) {
        const current = keepSelectedValue ?? $select.val();
        $select.empty();

        $select.append(`<option value="">Open this select menu</option>`);

        if (items && items.length) {
            items.forEach(item => {
                const safeText = $('<div/>').text(item.select_value).html();
                $select.append(`<option value="${safeText}">${safeText}</option>`);
            });
        }

        $select.append(`<option value="${MODIFY_VALUE}">** Modify List **</option>`);

        if (current && current !== MODIFY_VALUE) $select.val(current);
        else $select.val('');
    }

    function fetchOptions(dataType) {
        return $.getJSON(`/admin/select-input-lists/${encodeURIComponent(dataType)}/options`);
    }

    async function reloadSelect($select) {
        const dataType = $select.data('data-type');
        const res = await fetchOptions(dataType);
        renderSelectOptions($select, res.data);
    }

    async function reloadModalAndSelect() {
        const res = await fetchOptions(activeDataType);
        renderModalList(res.data);

        if (activeSelect) {
            renderSelectOptions(activeSelect, res.data);
        }
    }

    function openEditorForSelect($select) {
        activeSelect = $select;
        activeDataType = $select.data('data-type');

        $(sid('modalDataTypeLabel')).text(activeDataType);
        clearAlerts();
        resetEditorSelection();

        reloadModalAndSelect()
            .then(() => modal.show())
            .catch(() => showError('Failed to load list.'));
    }

    // Initialize all selects
    $(document).ready(function () {
        $('.js-select-input-list').each(function () {
            const $select = $(this);
            reloadSelect($select).catch(() => {
                $select.empty().append(`<option value="">Failed to load</option>`);
            });
        });
    });

    // Modify List selected
    $(document).on('change', '.js-select-input-list', function () {
        const $select = $(this);
        if ($select.val() === MODIFY_VALUE) {
            $select.val('');
            openEditorForSelect($select);
        }
    });

    // Click list item to edit
    $(document).on('click', `${sid('currentChoices')} .list-group-item`, function () {
        clearAlerts();
        $(`${sid('currentChoices')} .list-group-item`).removeClass('active');
        $(this).addClass('active');

        selectedItemId = $(this).data('id');
        $(sid('choiceInput')).val($(this).text().trim());
        $(`${sid('btnSave')}, ${sid('btnDelete')}`).prop('disabled', false);
    });

    // New
    $(sid('btnNew')).on('click', function () {
        clearAlerts();
        resetEditorSelection();
        $(sid('choiceInput')).focus();
    });

    // Add
    $(sid('btnAdd')).on('click', function () {
        clearAlerts();

        const value = $(sid('choiceInput')).val().trim();
        if (!value) return showError('Please enter a value.');

        $.ajax({
            url: '/admin/select-input-lists',
            method: 'POST',
            data: {
                data_type: activeDataType,
                select_value: value
            },
        })
            .done(async function () {
                showSuccess('Added.');
                resetEditorSelection();
                await reloadModalAndSelect();
            })
            .fail(function (xhr) {
                const msg = xhr?.responseJSON?.message ||
                    Object.values(xhr?.responseJSON?.errors || {}).flat()[0] ||
                    'Failed to add.';
                showError(msg);
            });
    });

    // Save (update)
    $(sid('btnSave')).on('click', function () {
        clearAlerts();

        if (!selectedItemId) return showError('Select an item to save.');
        const value = $(sid('choiceInput')).val().trim();
        if (!value) return showError('Please enter a value.');

        $.ajax({
            url: `/admin/select-input-lists/${selectedItemId}`,
            method: 'PUT',
            data: {
                select_value: value
            }
        })
            .done(async function () {
                showSuccess('Updated.');
                resetEditorSelection();
                await reloadModalAndSelect();
            })
            .fail(function (xhr) {
                const msg = xhr?.responseJSON?.message ||
                    Object.values(xhr?.responseJSON?.errors || {}).flat()[0] ||
                    'Failed to update.';
                showError(msg);
            });
    });

    // Delete
    $(sid('btnDelete')).on('click', function () {
        clearAlerts();

        if (!selectedItemId) return showError('Select an item to delete.');
        if (!confirm('Delete this value?')) return;

        $.ajax({
            url: `/admin/select-input-lists/${selectedItemId}`,
            method: 'DELETE'
        })
            .done(async function () {
                showSuccess('Deleted.');
                resetEditorSelection();
                await reloadModalAndSelect();
            })
            .fail(function (xhr) {
                showError(xhr?.responseJSON?.message || 'Failed to delete.');
            });
    });

    // Enter key
    $(sid('choiceInput')).on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedItemId) $(sid('btnSave')).click();
            else $(sid('btnAdd')).click();
        }
    });

    // Modal close reset
    modalEl.addEventListener('hidden.bs.modal', function () {
        activeSelect = null;
        activeDataType = null;
        resetEditorSelection();
        clearAlerts();
    });

})(jQuery);