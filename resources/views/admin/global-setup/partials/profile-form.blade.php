@php
    $profileSettings = $profile->settings_with_defaults;
    $isPersisted = (bool) $profile->exists;
    $formAction = $isPersisted ? route('global_setup_update', $profile->id) : route('global_setup_store');
@endphp

<form class="global-setup-form" method="POST" action="{{ $formAction }}" novalidate>
    @csrf

    <x-admin.form-section
        title="Profile Details"
        description="Create one reusable configuration profile for localization, modal behavior, labels and inputs."
        icon="tabler-adjustments-horizontal"
        class="mb-4"
    >
        @foreach ($profileFields as $field)
            <x-admin.form-field :field="$field" :model="$profile" />
        @endforeach
    </x-admin.form-section>

    @foreach ($settingSections as $section)
        <x-admin.form-section
            :title="$section['title']"
            :description="$section['description']"
            :icon="$section['icon']"
            class="mb-4"
        >
            @foreach ($section['fields'] as $field)
                <x-admin.form-field :field="$field" :model="$profileSettings" />
            @endforeach
        </x-admin.form-section>
    @endforeach

    <div class="d-flex flex-wrap justify-content-between gap-2">
        @if ($isPersisted)
            <button
                type="button"
                class="btn btn-label-danger btn-delete-global-setup"
                data-id="{{ $profile->id }}"
                data-name="{{ $profile->name }}"
            >
                Delete Profile
            </button>
        @else
            <span class="text-muted small align-self-center">Create the first profile, then you can reuse or fine tune it later.</span>
        @endif

        <button type="submit" class="btn btn-primary">
            {{ $isPersisted ? 'Save Changes' : 'Create Profile' }}
        </button>
    </div>
</form>
