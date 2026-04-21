<?php
// app/Http/Controllers/Admin/UiConfigurationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UiConfigurationRequest;
use App\Models\UiConfiguration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UiConfigurationController extends Controller
{
    public function index(): View
    {
        $configurations = UiConfiguration::query()
            ->orderBy('config_name')
            ->get();

        return view('admin.ui-configurations.index', compact('configurations'));
    }

    public function create(): View
    {
        $configuration = new UiConfiguration(UiConfiguration::defaults());

        return view('admin.ui-configurations.form', [
            'configuration' => $configuration,
            'isEdit' => false,
            'options' => config('ui-setup'),
        ]);
    }

    public function store(UiConfigurationRequest $request)
    {
        $configuration = UiConfiguration::create($request->validated());

        return $this->responseSuccess(
            $request,
            $configuration,
            'UI configuration created successfully.'
        );
    }

    public function edit(UiConfiguration $ui_configuration): View
    {
        return view('admin.ui-configurations.form', [
            'configuration' => $ui_configuration,
            'isEdit' => true,
            'options' => config('ui-setup'),
        ]);
    }

    public function update(UiConfigurationRequest $request, UiConfiguration $ui_configuration)
    {
        $ui_configuration->update($request->validated());

        return $this->responseSuccess(
            $request,
            $ui_configuration,
            'UI configuration updated successfully.'
        );
    }

    protected function responseSuccess(
        Request $request,
        UiConfiguration $configuration,
        string $message
    ): RedirectResponse|\Illuminate\Http\JsonResponse {
        $redirectUrl = route('setting.ui_configuration.edit', $configuration);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'redirect_url' => $redirectUrl,
                'data' => $configuration->fresh(),
            ]);
        }

        return redirect()
            ->route('setting.ui_configuration.edit', $configuration)
            ->with('success', $message);
    }
}
