<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SelectInputListAdminController;

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



Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/page1', [SelectInputListAdminController::class, 'index'])->name('admin.page1');

    Route::get('/select-input-lists/{data_type}/options', [SelectInputListAdminController::class, 'options'])
        ->name('admin.selectInputLists.options');

    Route::post('/select-input-lists', [SelectInputListAdminController::class, 'store'])
        ->name('admin.selectInputLists.store');

    Route::put('/select-input-lists/{selectInputList}', [SelectInputListAdminController::class, 'update'])
        ->name('admin.selectInputLists.update');

    Route::delete('/select-input-lists/{selectInputList}', [SelectInputListAdminController::class, 'destroy'])
        ->name('admin.selectInputLists.destroy');
});

require __DIR__ . '/auth.php';
