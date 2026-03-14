<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetupProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GlobalSetupAdminController extends Controller
{
    public function index()
    {
        $profiles = GlobalSetupProfile::query()
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        return view('admin.global-setup.index', [
            'profiles' => $profiles,
            'newProfile' => new GlobalSetupProfile([
                'is_active' => true,
                'settings' => GlobalSetupProfile::defaultSettings(),
            ]),
            'profileFields' => $this->profileFields(),
            'settingSections' => $this->settingSections(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateProfile($request);

        DB::beginTransaction();
        try {
            $profile = new GlobalSetupProfile();
            $this->saveProfile($profile, $validated);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Global setup profile created successfully.',
                'redirect_url' => route('global_setup_index', ['tab' => $profile->id]),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, GlobalSetupProfile $globalSetupProfile): JsonResponse
    {
        $validated = $this->validateProfile($request, $globalSetupProfile);

        DB::beginTransaction();
        try {
            $this->saveProfile($globalSetupProfile, $validated);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Global setup profile updated successfully.',
                'redirect_url' => route('global_setup_index', ['tab' => $globalSetupProfile->id]),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(GlobalSetupProfile $globalSetupProfile): JsonResponse
    {
        DB::beginTransaction();
        try {
            $globalSetupProfile->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Global setup profile deleted successfully.',
                'redirect_url' => route('global_setup_index'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function validateProfile(Request $request, ?GlobalSetupProfile $profile = null): array
    {
        return $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('global_setup_profiles', 'code')->ignore($profile?->id),
            ],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'settings.localization.date_format' => ['required', Rule::in(['d-m-Y', 'm/d/Y', 'Y-m-d'])],
            'settings.localization.time_format' => ['required', Rule::in(['H:i', 'h:i A'])],
            'settings.localization.date_separator' => ['required', Rule::in(['-', '/', '.'])],
            'settings.localization.timezone' => ['required', 'string', 'max:100'],
            'settings.localization.currency_symbol' => ['required', 'string', 'max:10'],
            'settings.localization.search_wildcard' => ['nullable', 'boolean'],
            'settings.modal.frame_width' => ['required', 'integer', 'min:320', 'max:1920'],
            'settings.modal.frame_height' => ['required', 'integer', 'min:240', 'max:1400'],
            'settings.modal.overlay_close' => ['nullable', 'boolean'],
            'settings.label.font_family' => ['required', 'string', 'max:50'],
            'settings.label.font_size' => ['required', 'integer', 'min:8', 'max:32'],
            'settings.label.color' => ['required', 'string', 'max:20'],
            'settings.label.weight' => ['required', Rule::in(['400', '500', '600', '700'])],
            'settings.label.position' => ['required', Rule::in(['top', 'left', 'floating'])],
            'settings.input.font_family' => ['required', 'string', 'max:50'],
            'settings.input.font_size' => ['required', 'integer', 'min:8', 'max:32'],
            'settings.input.color' => ['required', 'string', 'max:20'],
            'settings.input.margin' => ['required', 'integer', 'min:0', 'max:40'],
        ]);
    }

    protected function saveProfile(GlobalSetupProfile $profile, array $validated): void
    {
        $profile->code = $validated['code'];
        $profile->name = $validated['name'];
        $profile->description = $validated['description'] ?? null;
        $profile->is_active = (bool) ($validated['is_active'] ?? false);
        $profile->settings = array_replace_recursive(
            GlobalSetupProfile::defaultSettings(),
            $this->buildSettingsPayload($validated)
        );
        $profile->save();
    }

    protected function buildSettingsPayload(array $validated): array
    {
        return [
            'localization' => [
                'date_format' => data_get($validated, 'settings.localization.date_format'),
                'time_format' => data_get($validated, 'settings.localization.time_format'),
                'date_separator' => data_get($validated, 'settings.localization.date_separator'),
                'timezone' => data_get($validated, 'settings.localization.timezone'),
                'currency_symbol' => data_get($validated, 'settings.localization.currency_symbol'),
                'search_wildcard' => (bool) data_get($validated, 'settings.localization.search_wildcard', false),
            ],
            'modal' => [
                'frame_width' => (int) data_get($validated, 'settings.modal.frame_width'),
                'frame_height' => (int) data_get($validated, 'settings.modal.frame_height'),
                'overlay_close' => (bool) data_get($validated, 'settings.modal.overlay_close', false),
            ],
            'label' => [
                'font_family' => data_get($validated, 'settings.label.font_family'),
                'font_size' => (int) data_get($validated, 'settings.label.font_size'),
                'color' => data_get($validated, 'settings.label.color'),
                'weight' => data_get($validated, 'settings.label.weight'),
                'position' => data_get($validated, 'settings.label.position'),
            ],
            'input' => [
                'font_family' => data_get($validated, 'settings.input.font_family'),
                'font_size' => (int) data_get($validated, 'settings.input.font_size'),
                'color' => data_get($validated, 'settings.input.color'),
                'margin' => (int) data_get($validated, 'settings.input.margin'),
            ],
        ];
    }

    protected function profileFields(): array
    {
        return [
            [
                'name' => 'code',
                'label' => 'Configuration Code',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'default',
                'help' => 'Use a short unique code like default, bbg or bpt.',
                'col' => 'col-lg-4 col-md-6',
            ],
            [
                'name' => 'name',
                'label' => 'Display Name',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Default Configuration',
                'col' => 'col-lg-4 col-md-6',
            ],
            [
                'name' => 'is_active',
                'label' => 'Active Configuration',
                'type' => 'switch',
                'default' => true,
                'col' => 'col-lg-4 col-md-6 d-flex align-items-end',
            ],
            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'textarea',
                'rows' => 3,
                'placeholder' => 'Optional notes for this configuration profile.',
                'col' => 'col-12',
            ],
        ];
    }

    protected function settingSections(): array
    {
        $fontOptions = [
            'Public Sans' => 'Public Sans',
            'Arial' => 'Arial',
            'Tahoma' => 'Tahoma',
            'Verdana' => 'Verdana',
        ];

        $colorOptions = [
            '#4b465c' => 'Black 100%',
            '#6f6b7d' => 'Gray 75%',
            '#8c57ff' => 'Primary Purple',
            '#28c76f' => 'Success Green',
        ];

        return [
            [
                'title' => 'Localization',
                'description' => 'Centralize date, time and currency behavior so forms and reports stay consistent.',
                'icon' => 'tabler-world',
                'fields' => [
                    [
                        'name' => 'settings[localization][date_format]',
                        'old_key' => 'settings.localization.date_format',
                        'value_path' => 'localization.date_format',
                        'error_key' => 'settings.localization.date_format',
                        'label' => 'Date Format',
                        'type' => 'select',
                        'options' => [
                            'd-m-Y' => 'UK, 31-12-2009',
                            'm/d/Y' => 'US, 12/31/2009',
                            'Y-m-d' => 'ISO, 2009-12-31',
                        ],
                        'col' => 'col-lg-4 col-md-6',
                    ],
                    [
                        'name' => 'settings[localization][time_format]',
                        'old_key' => 'settings.localization.time_format',
                        'value_path' => 'localization.time_format',
                        'error_key' => 'settings.localization.time_format',
                        'label' => 'Time Format',
                        'type' => 'select',
                        'options' => [
                            'H:i' => '24 Hour, 23:59',
                            'h:i A' => '12 Hour, 11:59 PM',
                        ],
                        'col' => 'col-lg-4 col-md-6',
                    ],
                    [
                        'name' => 'settings[localization][date_separator]',
                        'old_key' => 'settings.localization.date_separator',
                        'value_path' => 'localization.date_separator',
                        'error_key' => 'settings.localization.date_separator',
                        'label' => 'Date Separator',
                        'type' => 'select',
                        'options' => [
                            '-' => 'Dash (-)',
                            '/' => 'Slash (/)',
                            '.' => 'Dot (.)',
                        ],
                        'col' => 'col-lg-4 col-md-6',
                    ],
                    [
                        'name' => 'settings[localization][timezone]',
                        'old_key' => 'settings.localization.timezone',
                        'value_path' => 'localization.timezone',
                        'error_key' => 'settings.localization.timezone',
                        'label' => 'Timezone',
                        'type' => 'select',
                        'options' => [
                            'UTC' => 'UTC',
                            'Asia/Kuala_Lumpur' => 'Asia/Kuala Lumpur',
                            'Asia/Singapore' => 'Asia/Singapore',
                            'Europe/London' => 'Europe/London',
                            'America/New_York' => 'America/New York',
                        ],
                        'col' => 'col-lg-4 col-md-6',
                    ],
                    [
                        'name' => 'settings[localization][currency_symbol]',
                        'old_key' => 'settings.localization.currency_symbol',
                        'value_path' => 'localization.currency_symbol',
                        'error_key' => 'settings.localization.currency_symbol',
                        'label' => 'Currency Symbol',
                        'type' => 'text',
                        'placeholder' => 'RM',
                        'col' => 'col-lg-4 col-md-6',
                    ],
                    [
                        'name' => 'settings[localization][search_wildcard]',
                        'old_key' => 'settings.localization.search_wildcard',
                        'value_path' => 'localization.search_wildcard',
                        'error_key' => 'settings.localization.search_wildcard',
                        'label' => 'Search Wildcard',
                        'type' => 'select',
                        'options' => [
                            '1' => 'Yes',
                            '0' => 'No',
                        ],
                        'col' => 'col-lg-4 col-md-6',
                    ],
                ],
            ],
            [
                'title' => 'Modal Frame',
                'description' => 'Tune the default modal container so forms feel deliberate across the product.',
                'icon' => 'tabler-layout-modal',
                'fields' => [
                    [
                        'name' => 'settings[modal][frame_width]',
                        'old_key' => 'settings.modal.frame_width',
                        'value_path' => 'modal.frame_width',
                        'error_key' => 'settings.modal.frame_width',
                        'label' => 'Frame Width',
                        'type' => 'number',
                        'min' => 320,
                        'max' => 1920,
                        'col' => 'col-lg-4 col-md-6',
                    ],
                    [
                        'name' => 'settings[modal][frame_height]',
                        'old_key' => 'settings.modal.frame_height',
                        'value_path' => 'modal.frame_height',
                        'error_key' => 'settings.modal.frame_height',
                        'label' => 'Frame Height',
                        'type' => 'number',
                        'min' => 240,
                        'max' => 1400,
                        'col' => 'col-lg-4 col-md-6',
                    ],
                    [
                        'name' => 'settings[modal][overlay_close]',
                        'old_key' => 'settings.modal.overlay_close',
                        'value_path' => 'modal.overlay_close',
                        'error_key' => 'settings.modal.overlay_close',
                        'label' => 'Overlay Close',
                        'type' => 'select',
                        'options' => [
                            '1' => 'Yes',
                            '0' => 'No',
                        ],
                        'col' => 'col-lg-4 col-md-6',
                    ],
                ],
            ],
            [
                'title' => 'Label Style',
                'description' => 'Define default label styling once, then reuse it across admin forms.',
                'icon' => 'tabler-text-caption',
                'fields' => [
                    [
                        'name' => 'settings[label][font_family]',
                        'old_key' => 'settings.label.font_family',
                        'value_path' => 'label.font_family',
                        'error_key' => 'settings.label.font_family',
                        'label' => 'Font',
                        'type' => 'select',
                        'options' => $fontOptions,
                        'col' => 'col-lg-3 col-md-6',
                    ],
                    [
                        'name' => 'settings[label][font_size]',
                        'old_key' => 'settings.label.font_size',
                        'value_path' => 'label.font_size',
                        'error_key' => 'settings.label.font_size',
                        'label' => 'Size',
                        'type' => 'number',
                        'min' => 8,
                        'max' => 32,
                        'col' => 'col-lg-3 col-md-6',
                    ],
                    [
                        'name' => 'settings[label][color]',
                        'old_key' => 'settings.label.color',
                        'value_path' => 'label.color',
                        'error_key' => 'settings.label.color',
                        'label' => 'Color',
                        'type' => 'select',
                        'options' => $colorOptions,
                        'col' => 'col-lg-3 col-md-6',
                    ],
                    [
                        'name' => 'settings[label][weight]',
                        'old_key' => 'settings.label.weight',
                        'value_path' => 'label.weight',
                        'error_key' => 'settings.label.weight',
                        'label' => 'Weight',
                        'type' => 'select',
                        'options' => [
                            '400' => 'Regular',
                            '500' => 'Medium',
                            '600' => 'Semi Bold',
                            '700' => 'Bold',
                        ],
                        'col' => 'col-lg-3 col-md-6',
                    ],
                    [
                        'name' => 'settings[label][position]',
                        'old_key' => 'settings.label.position',
                        'value_path' => 'label.position',
                        'error_key' => 'settings.label.position',
                        'label' => 'Position',
                        'type' => 'select',
                        'options' => [
                            'top' => 'Top of input field',
                            'left' => 'Left of input field',
                            'floating' => 'Floating label',
                        ],
                        'col' => 'col-lg-4 col-md-6',
                    ],
                ],
            ],
            [
                'title' => 'Input Style',
                'description' => 'Keep input typography and spacing consistent for every form component you reuse.',
                'icon' => 'tabler-text-wrap',
                'fields' => [
                    [
                        'name' => 'settings[input][font_family]',
                        'old_key' => 'settings.input.font_family',
                        'value_path' => 'input.font_family',
                        'error_key' => 'settings.input.font_family',
                        'label' => 'Font',
                        'type' => 'select',
                        'options' => $fontOptions,
                        'col' => 'col-lg-4 col-md-6',
                    ],
                    [
                        'name' => 'settings[input][font_size]',
                        'old_key' => 'settings.input.font_size',
                        'value_path' => 'input.font_size',
                        'error_key' => 'settings.input.font_size',
                        'label' => 'Size',
                        'type' => 'number',
                        'min' => 8,
                        'max' => 32,
                        'col' => 'col-lg-4 col-md-6',
                    ],
                    [
                        'name' => 'settings[input][color]',
                        'old_key' => 'settings.input.color',
                        'value_path' => 'input.color',
                        'error_key' => 'settings.input.color',
                        'label' => 'Color',
                        'type' => 'select',
                        'options' => $colorOptions,
                        'col' => 'col-lg-4 col-md-6',
                    ],
                    [
                        'name' => 'settings[input][margin]',
                        'old_key' => 'settings.input.margin',
                        'value_path' => 'input.margin',
                        'error_key' => 'settings.input.margin',
                        'label' => 'Vertical Margin',
                        'type' => 'number',
                        'min' => 0,
                        'max' => 40,
                        'col' => 'col-lg-4 col-md-6',
                    ],
                ],
            ],
        ];
    }
}
