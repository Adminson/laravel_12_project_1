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
    <x-alert.alert-session />

    <form
        action="{{ route('setting.system_message.update', [
            'company_profile' => $company->cmp_id,
            'system_message' => $systemMessage->msg_id,
        ]) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        @include('admin.company.system-message._form')
    </form>
@endsection
