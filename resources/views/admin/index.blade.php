@extends('layouts.app')

@section('content')
    <h4 class="py-4 mb-6">Page 1</h4>
    <p>
        Sample page.<br />
        For more layout options use
        <a href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation//layouts.html" target="_blank" class="fw-medium">
            Layout docs
        </a>.
    </p>

    <div class="mb-4">
        <label for="exampleFormControlSelect1" class="form-label">Select</label>

        {{-- Set your data_type here --}}
        <select class="form-select js-select-input-list" id="exampleFormControlSelect1" data-data-type="transfer_type" aria-label="Default select example">
            <option value="" selected>Loading...</option>
        </select>
        <br />
        <select class="form-select js-select-input-list" data-data-type="room_type"></select>
        <br />
        <select class="form-select js-select-input-list" data-data-type="transfer_type_car"></select>

        <div class="form-text">Options are loaded from DB by <code>data_type</code>.</div>
    </div>

    <x-admin.modify-list-modal />
@endsection

@push('scripts')
    <script></script>
@endpush
