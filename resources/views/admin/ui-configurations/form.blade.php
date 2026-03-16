{{-- resources/views/admin/ui-configurations/form.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ $isEdit ? 'Edit UI Configuration' : 'Create UI Configuration' }}</h4>
        <x-ui.link
            :href="route('setting.ui_configuration.index')"
            variant="secondary"
        >
            Back
        </x-ui.link>
    </div>

    <x-alert.alert-session />
    @php
        $fontOptionAttributes = collect($options['font_families'])
            ->mapWithKeys(
                fn($label, $key) => [
                    $key => ['data-font' => $key],
                ],
            )
            ->toArray();
    @endphp

    <form
        method="POST"
       action="{{ $isEdit ? route('setting.ui_configuration.update', ['ui_configuration' => $configuration->id]) : route('setting.ui_configuration.store') }}"
        novalidate
    >
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="row g-3">
            {{-- Configuration Profile --}}
            <div class="col-md-4">
                <x-card.card
                    title="Configuration Profile"
                >
                    <div class="mb-3">
                        <x-form.input-label
                            for="config_key"
                            value="Configuration Key"
                            :required="true"
                        />
                        <x-form.input-text
                            name="config_key"
                            id="config_key"
                            :value="$configuration->config_key ?? ''"
                            placeholder="Example: bbg"
                            :readonly="$isEdit"
                            :required="true"
                        />
                    </div>

                    <div class="mb-3">
                        <x-form.input-label
                            for="config_name"
                            value="Configuration Name"
                            :required="true"
                        />
                        <x-form.input-text
                            name="config_name"
                            id="config_name"
                            :value="$configuration->config_name ?? ''"
                            placeholder="Example: BBG Configuration"
                            :required="true"
                        />
                    </div>

                    <div class="mb-0">
                        <x-form.input-label
                            for="is_active"
                            value="Active"
                            :required="true"
                        />
                        <x-form.input-select
                            name="is_active"
                            id="is_active"
                            :options="$options['yes_no']"
                            :value="(int) ($configuration->is_active ?? 1)"
                            :required="true"
                        />
                    </div>
                </x-card.card>
            </div>

            {{-- Localization --}}
            <div class="col-md-4">
                <x-card.card title="Localization">
                    <div class="mb-3">
                        <x-form.input-label
                            for="date_format"
                            value="Date Format"
                            :required="true"
                        />
                        <x-form.input-select
                            name="date_format"
                            id="date_format"
                            :options="$options['date_formats']"
                            :value="$configuration->date_format ?? ''"
                            :required="true"
                        />
                    </div>

                    <div class="mb-3">
                        <x-form.input-label
                            for="time_format"
                            value="Time Format"
                            :required="true"
                        />
                        <x-form.input-select
                            name="time_format"
                            id="time_format"
                            :options="$options['time_formats']"
                            :value="$configuration->time_format ?? ''"
                            :required="true"
                        />
                    </div>

                    <div class="mb-3">
                        <x-form.input-label
                            for="date_separator"
                            value="Date Separator"
                            :required="true"
                        />
                        <x-form.input-select
                            name="date_separator"
                            id="date_separator"
                            :options="$options['date_separators']"
                            :value="$configuration->date_separator ?? ''"
                            :required="true"
                        />
                    </div>

                    <div class="mb-3">
                        <x-form.input-label
                            for="currency_symbol"
                            value="Currency Symbol"
                        />
                        <x-form.input-text
                            name="currency_symbol"
                            id="currency_symbol"
                            :value="$configuration->currency_symbol ?? ''"
                            placeholder="Example: RM"
                        />
                    </div>

                    <div class="mb-0">
                        <x-form.input-label
                            for="search_wildcard_enabled"
                            value="Wildcard Search"
                            :required="true"
                        />
                        <x-form.input-select
                            name="search_wildcard_enabled"
                            id="search_wildcard_enabled"
                            :options="$options['yes_no']"
                            :value="(int) ($configuration->search_wildcard_enabled ?? 1)"
                            :required="true"
                        />
                    </div>
                </x-card.card>
            </div>

            {{-- Modal Frame --}}
            <div class="col-md-4">
                <x-card.card title="Modal Frame">
                    <div class="mb-3">
                        <x-form.input-label
                            for="modal_frame_width"
                            value="Frame Width"
                            :required="true"
                        />
                        <x-form.input-text-group
                            name="modal_frame_width"
                            id="modal_frame_width"
                            type="number"
                            :value="$configuration->modal_frame_width ?? ''"
                            min="0"
                            :required="true"
                        />
                    </div>

                    <div class="mb-3">
                        <x-form.input-label
                            for="modal_frame_height"
                            value="Frame Height"
                            :required="true"
                        />
                        <x-form.input-text-group
                            name="modal_frame_height"
                            id="modal_frame_height"
                            type="number"
                            :value="$configuration->modal_frame_height ?? ''"
                            min="0"
                            :required="true"
                        />
                    </div>

                    <div class="mb-0">
                        <x-form.input-label
                            for="modal_overlay_close_enabled"
                            value="Overlay Close"
                            :required="true"
                        />
                        <x-form.input-select
                            name="modal_overlay_close_enabled"
                            id="modal_overlay_close_enabled"
                            :options="$options['yes_no']"
                            :value="(int) ($configuration->modal_overlay_close_enabled ?? 1)"
                            :required="true"
                        />
                    </div>
                </x-card.card>
            </div>
        </div>

        <div class="row g-3 mt-1">
            {{-- Label --}}
            <div class="col-md-4">
                <x-card.card title="Label">
                    <div class="mb-3">
                        <x-form.input-label
                            for="label_font_family"
                            value="Font"
                            :required="true"
                        />
                        <x-form.input-select2
                            name="label_font_family"
                            id="label_font_family"
                            :options="$options['font_families']"
                            :option-attributes="$fontOptionAttributes"
                            :value="$configuration->label_font_family ?? ''"
                            class="font-family-select"
                            :required="true"
                        />
                    </div>

                    <div class="mb-3">
                        <x-form.input-label
                            for="label_font_size"
                            value="Size"
                            :required="true"
                        />
                        <x-form.input-text-group
                            name="label_font_size"
                            id="label_font_size"
                            type="number"
                            :value="$configuration->label_font_size ?? ''"
                            min="1"
                            :required="true"
                        />
                    </div>

                    <div class="mb-3">
                        <x-form.input-label
                            for="label_color"
                            value="Color"
                            :required="true"
                        />

                        <x-form.input-select-group-color
                            name="label_color"
                            id="label_color"
                            :options="$options['colors']"
                            :value="$configuration->label_color ?? ''"
                            class="color-select"
                            data-preview="#label_color_preview"
                            :required="true"
                        />
                    </div>

                    <div class="mb-3">
                        <x-form.input-label
                            for="label_font_weight"
                            value="Weight"
                            :required="true"
                        />
                        <x-form.input-select
                            name="label_font_weight"
                            id="label_font_weight"
                            :options="$options['font_weights']"
                            :value="$configuration->label_font_weight ?? ''"
                            :required="true"
                        />
                    </div>

                    <div class="mb-0">
                        <x-form.input-label
                            for="label_position"
                            value="Position"
                            :required="true"
                        />
                        <x-form.input-select
                            name="label_position"
                            id="label_position"
                            :options="$options['label_positions']"
                            :value="$configuration->label_position ?? ''"
                            :required="true"
                        />
                    </div>
                </x-card.card>
            </div>

            {{-- Input --}}
            <div class="col-md-4">
                <x-card.card title="Input">
                    <div class="mb-3">
                        <x-form.input-label
                            for="input_font_family"
                            value="Font"
                            :required="true"
                        />
                        <x-form.input-select2
                            name="input_font_family"
                            id="input_font_family"
                            :options="$options['font_families']"
                            :option-attributes="$fontOptionAttributes"
                            :value="$configuration->input_font_family ?? ''"
                            class="font-family-select"
                            :required="true"
                        />
                    </div>

                    <div class="mb-3">
                        <x-form.input-label
                            for="input_font_size"
                            value="Size"
                            :required="true"
                        />
                        <x-form.input-text-group
                            name="input_font_size"
                            id="input_font_size"
                            type="number"
                            :value="$configuration->input_font_size ?? ''"
                            min="1"
                            :required="true"
                        />
                        @error('input_font_size')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <x-form.input-label
                            for="input_color"
                            value="Color"
                            :required="true"
                        />
                        <x-form.input-select-group-color
                            name="input_color"
                            id="input_color"
                            :options="$options['colors']"
                            :value="$configuration->input_color ?? ''"
                            class="color-select"
                            data-preview="#input_color_preview"
                            :required="true"
                        />
                    </div>

                    <div class="mb-0">
                        <x-form.input-label
                            for="input_margin"
                            value="Margin Bottom"
                            :required="true"
                        />
                        <x-form.input-text-group
                            name="input_margin"
                            id="input_margin"
                            type="number"
                            :value="$configuration->input_margin ?? ''"
                            min="0"
                            :required="true"
                        />
                    </div>

                </x-card.card>
            </div>
        </div>

        <div class="text-end mt-4">
            <a
                href="{{ route('setting.ui_configuration.index') }}"
                class="btn btn-label-secondary"
            >Back</a>
            <button
                type="submit"
                class="btn btn-primary"
            >
                {{ $isEdit ? 'Save Changes' : 'Create Configuration' }}
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.color-select').on('change', function() {
                const previewSelector = $(this).data('preview');
                $(previewSelector).css('background-color', $(this).val());
            }).trigger('change');
        });
    </script>

    <script>
        $(document).ready(function() {
            function formatFont(option) {
                if (!option.id) {
                    return option.text;
                }

                const fontFamily = $(option.element).data('font') || option.text;

                return $('<span>', {
                    text: option.text,
                    css: {
                        fontFamily: fontFamily,
                        fontSize: '16px'
                    }
                });
            }

            $('#label_font_family, #input_font_family').select2({
                width: '100%',
                templateResult: formatFont,
                templateSelection: formatFont,
                escapeMarkup: function(markup) {
                    return markup;
                }
            });
        });
    </script>
@endpush
