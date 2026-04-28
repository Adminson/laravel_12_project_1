<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CompanyAdminController;
use App\Http\Controllers\Admin\LicenseAdminController;
use App\Http\Controllers\Admin\MemoController;
use App\Http\Controllers\Admin\SelectInputListAdminController;
use App\Http\Controllers\Admin\SystemMessageAdminController;
use App\Http\Controllers\Admin\UiConfigurationController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('setting')
    ->middleware(['auth'])
    ->name('setting.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | User Setting
        |--------------------------------------------------------------------------
        */
        // setting/user
        Route::prefix('user')
            ->name('user.')
            ->group(function () {
                Route::get('/', [UserAdminController::class, 'index'])->name('index');        // setting.user.index
                Route::get('/list', [UserAdminController::class, 'list'])->name('list');     // setting.user.list
                Route::post('/store', [UserAdminController::class, 'store'])->name('store'); // setting.user.store
                Route::get('/{user}', [UserAdminController::class, 'show'])->name('show');   // setting.user.show
                Route::put('/{user}', [UserAdminController::class, 'update'])->name('update'); // setting.user.update
            });

        /*
        |--------------------------------------------------------------------------
        | Company Setting
        |--------------------------------------------------------------------------
        */
        Route::prefix('company')
            ->name('company.')
            ->group(function () {
                Route::get('/', [CompanyAdminController::class, 'index'])->name('index'); //setting.company.index
                Route::get('/list', [CompanyAdminController::class, 'list'])->name('list');

                Route::get('/create', [CompanyAdminController::class, 'create'])->name('create');
                Route::post('/store', [CompanyAdminController::class, 'store'])->name('store');

                Route::get('/{company_profile}/edit', [CompanyAdminController::class, 'edit'])->name('edit');
                Route::post('/{company_profile}/update', [CompanyAdminController::class, 'update'])->name('update');
                Route::delete('/{company_profile}', [CompanyAdminController::class, 'destroy'])->name('delete');
                // Route::post('/test-pdf', [CompanyAdminController::class, 'testPdf'])->name('test_pdf');
                Route::get('/{company_profile}/test-pdf', [CompanyAdminController::class, 'testPdf'])->name('test_pdf');
            });

        /*
        |--------------------------------------------------------------------------
        | System Message
        |--------------------------------------------------------------------------
        */
        Route::prefix('system-message')
            ->name('system_message.')
            ->group(function () {
                Route::get('/{company_profile}', [SystemMessageAdminController::class, 'index'])->name('index');
                Route::get('/{company_profile}/list', [SystemMessageAdminController::class, 'list'])->name('list');

                Route::get('/{company_profile}/create', [SystemMessageAdminController::class, 'create'])->name('create');
                Route::post('/{company_profile}/store', [SystemMessageAdminController::class, 'store'])->name('store');

                Route::get('/{company_profile}/{system_message}/edit', [SystemMessageAdminController::class, 'edit'])->name('edit'); //setting/system-message/{company_profile}/{system_message}/edit
                Route::put('/{company_profile}/{system_message}/update', [SystemMessageAdminController::class, 'update'])->name('update');

                Route::delete('/{company_profile}/{system_message}', [SystemMessageAdminController::class, 'destroy'])->name('delete');
                Route::post('/{company_profile}/system-message/{system_message}/resend-email', [SystemMessageAdminController::class, 'resendEmail'])->name('resend_email');


                Route::get('/{company_profile}/system-message/{system_message}/email-log-list', [SystemMessageAdminController::class, 'emailLogList'])->name('email_log_list'); //setting.system_message.email_log_list
            });
        Route::prefix('license')
            ->name('license.')
            ->group(function () {
                Route::get('/{company_profile}', [LicenseAdminController::class, 'index'])->name('index');
            });
        /*
        |--------------------------------------------------------------------------
        | UI Configuration
        |--------------------------------------------------------------------------
        */
        Route::prefix('ui-configuration')
            ->name('ui_configuration.')
            ->group(function () {
                Route::get('/', [UiConfigurationController::class, 'index'])->name('index');
                Route::get('/list', [UiConfigurationController::class, 'list'])->name('list');

                Route::get('/create', [UiConfigurationController::class, 'create'])->name('create');
                Route::post('/store', [UiConfigurationController::class, 'store'])->name('store');

                Route::get('/{ui_configuration}/edit', [UiConfigurationController::class, 'edit'])->name('edit');
                Route::put('/{ui_configuration}/update', [UiConfigurationController::class, 'update'])->name('update');
            });

        /*
        |--------------------------------------------------------------------------
        | Select Input List
        |--------------------------------------------------------------------------
        */
        Route::prefix('select-input-list')
            ->name('select_input_list.')
            ->group(function () {
                Route::get('/', [SelectInputListAdminController::class, 'index'])->name('index');
                Route::get('/list', [SelectInputListAdminController::class, 'list'])->name('list');
                Route::get('/{data_type}/options', [SelectInputListAdminController::class, 'options'])->name('options');

                Route::post('/store', [SelectInputListAdminController::class, 'store'])->name('store');
                Route::put('/{select_input_list}/update', [SelectInputListAdminController::class, 'update'])->name('update');
                Route::delete('/{select_input_list}', [SelectInputListAdminController::class, 'delete'])->name('delete');
            });

        Route::prefix('memos')
            ->name('memos.')
            ->group(function () {
                Route::post('/store', [MemoController::class, 'store'])->name('store');
                Route::delete('/{memo}', [MemoController::class, 'destroy'])->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Audit Logs
        |--------------------------------------------------------------------------
        */
        Route::prefix('audit')
            ->name('audit.')
            ->group(function () {
                Route::get('/audit-list/{id}/{type}', [AuditLogController::class, 'list'])->name('audit-list'); //setting.audit.audit-list

                Route::get('/audit-full-list', [AuditLogController::class, 'fullList'])->name('audit-full-list');
                Route::get('/audit-full-list-data', [AuditLogController::class, 'fullListData'])->name('audit-full-list-data');
            });
    });

require __DIR__ . '/auth.php';
