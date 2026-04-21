@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a
                class="nav-link"
                href="{{ route('setting.company.edit', $company->cmp_id) }}"
            >
                Company Details
            </a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link active"
                href="{{ route('setting.system_message.index', $company->cmp_id) }}"
            >
                System Message
            </a>
        </li>
    </ul>



    <form
        action="{{ route('setting.system_message.store', $company->cmp_id) }}"
        method="POST"
    >
        @csrf

        @include('admin.company.system-message._form')
    </form>
@endsection
