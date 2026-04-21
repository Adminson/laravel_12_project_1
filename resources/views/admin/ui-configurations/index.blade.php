{{-- resources/views/admin/ui-configurations/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Global UI Configurations</h4>
                <a href="{{ route('setting.ui_configuration.create') }}" class="btn btn-primary">New Configuration</a>
            </div>

            <ul class="nav nav-tabs">
                @foreach ($configurations as $item)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('setting.ui_configuration.edit', $item) }}">
                            Configuration: {{ $item->config_key }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection