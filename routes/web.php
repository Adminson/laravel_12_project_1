<?php

use App\Http\Controllers\Admin\CompanyAdminController;
use App\Http\Controllers\Admin\MemoController;
use App\Http\Controllers\Admin\SelectInputListAdminController;
use App\Http\Controllers\Admin\SystemMessageAdminController;
use App\Http\Controllers\Admin\UiConfigurationController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
                Route::get('/', [UserAdminController::class, 'index'])->name('index'); //setting.user.index
                Route::get('/list', [UserAdminController::class, 'list'])->name('list'); //setting.user.list
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

                Route::get('/{company_profile}/{system_message}/edit', [SystemMessageAdminController::class, 'edit'])->name('edit');
                Route::put('/{company_profile}/{system_message}/update', [SystemMessageAdminController::class, 'update'])->name('update');

                Route::delete('/{company_profile}/{system_message}', [SystemMessageAdminController::class, 'destroy'])->name('delete');
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
    });

require __DIR__ . '/auth.php';
