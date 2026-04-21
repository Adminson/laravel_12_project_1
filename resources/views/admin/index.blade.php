@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <label for="exampleFormControlSelect1" class="form-label">Select</label>

        <select class="form-select js-select-input-list" id="exampleFormControlSelect1" data-data-type="transfer_type" aria-label="Default select example">
            <option value="" selected>Loading...</option>
        </select>
        <br />
        <select class="form-select js-select-input-list" data-data-type="room_type"></select>
        <br />
        <select class="form-select js-select-input-list" data-data-type="transfer_type_car"></select>

        {{-- <div class="form-text">Options are loaded from DB by <code>data_type</code>.</div> --}}
    </div>

    <x-admin.modify-list-modal />
@endsection

@push('scripts')
            <script>
            $(document).ready(function() {

                // Example: use appAjax for some custom call
                appAjax({
                    url: '{{ route('setting.user.list') }}',
                    method: 'GET',
                    onSuccess: function(res) {
                        console.log('User list loaded', res);
                    }
                });

                // Your DataTable and jQuery Validation code here...

            });
        </script>
@endpush
