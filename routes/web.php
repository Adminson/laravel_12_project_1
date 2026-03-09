<?php

use App\Http\Controllers\Admin\SelectInputListAdminController;
use App\Http\Controllers\Admin\CompanyAdminController;
use App\Http\Controllers\Admin\SystemMessageAdminController;
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


Route::prefix('admin')
    ->middleware(['auth'])
    ->name('admin_select_input_lists_')
    ->group(function () {
        Route::get('/select-example', [SelectInputListAdminController::class, 'index'])->name('index');
        Route::get('/select-input-lists/{data_type}/options', [SelectInputListAdminController::class, 'options'])->name('options');
        Route::post('/select-input-lists', [SelectInputListAdminController::class, 'store'])->name('store');
        Route::put('/select-input-lists/{selectInputList}', [SelectInputListAdminController::class, 'update'])->name('update');
        Route::delete('/select-input-lists/{selectInputList}', [SelectInputListAdminController::class, 'destroy'])->name('destroy');
    });

Route::prefix('admin/company-setting')
    ->middleware(['auth'])
    ->name('company_setting_')
    ->group(function () {
        Route::get('/', [SelectInputListAdminController::class, 'index'])->name('index');
        Route::get('/list', [SelectInputListAdminController::class, 'list'])->name('list');
    });

Route::prefix('admin/user-setting')
    ->middleware(['auth'])
    ->name('user_setting_')
    ->group(function () {
        Route::get('/', [UserAdminController::class, 'index'])->name('index');
        Route::get('/list', [UserAdminController::class, 'list'])->name('list');
    });




Route::prefix('admin/company-setting')
    ->middleware(['auth'])
    ->name('company_setting_')
    ->group(function () {
        Route::get('/', [CompanyAdminController::class, 'index'])->name('index');
        Route::get('/list', [CompanyAdminController::class, 'list'])->name('list');

        Route::get('/create', [CompanyAdminController::class, 'create'])->name('create');
        Route::post('/store', [CompanyAdminController::class, 'store'])->name('store');

        Route::get('/{companyProfile}/edit', [CompanyAdminController::class, 'edit'])->name('edit');
        Route::post('/{companyProfile}/update', [CompanyAdminController::class, 'update'])->name('update');
        Route::delete('/{companyProfile}', [CompanyAdminController::class, 'destroy'])->name('delete');
    });

Route::prefix('admin/system-message')
    ->middleware(['auth'])
    ->name('system_message_')
    ->group(function () {
        Route::get('/{companyProfile}', [SystemMessageAdminController::class, 'index'])->name('index');
        Route::get('/{companyProfile}/list', [SystemMessageAdminController::class, 'list'])->name('list');

        Route::post('/{companyProfile}/store', [SystemMessageAdminController::class, 'store'])->name('store');
        Route::get('/item/{systemMessage}', [SystemMessageAdminController::class, 'show'])->name('show');
        Route::post('/item/{systemMessage}/update', [SystemMessageAdminController::class, 'update'])->name('update');
        Route::delete('/item/{systemMessage}', [SystemMessageAdminController::class, 'destroy'])->name('delete');
    });

require __DIR__ . '/auth.php';
