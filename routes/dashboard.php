<?php


use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\CentralsController;

use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['prefix' => 'dashboard', 'middleware' => ['role:superadministrator|administrator']], function () {

    // admin users routes
    Route::resource('users', UsersController::class)->middleware('auth');
    Route::get('users/export/', [UsersController::class, 'export'])->name('users.export')->middleware('auth');
    Route::get('/trashed-users', [UsersController::class, 'trashed'])->name('users.trashed')->middleware('auth');
    Route::get('/trashed-users/{user}', [UsersController::class, 'restore'])->name('users.restore')->middleware('auth');
    Route::get('/activate-users/{user}', [UsersController::class, 'activate'])->name('users.activate')->middleware('auth');
    Route::get('/block-users/{user}', [UsersController::class, 'block'])->name('users.block')->middleware('auth');
    Route::post('/add-bonus/{user}', [UsersController::class, 'bonus'])->name('users.bonus')->middleware('auth');


    // roles routes
    Route::resource('roles',  RoleController::class)->middleware('auth');
    Route::get('/trashed-roles', [RoleController::class, 'trashed'])->name('roles.trashed')->middleware('auth');
    Route::get('/trashed-roles/{role}', [RoleController::class, 'restore'])->name('roles.restore')->middleware('auth');
});

// Centrals routes
Route::resource('Centrals', CentralsController::class)->middleware('auth', 'checkverified', 'checkstatus');
Route::get('/trashed-Centrals', [CentralsController::class, 'trashed'])->name('Centrals.trashed')->middleware('auth', 'checkverified', 'checkstatus');
Route::get('/trashed-Centrals/{Centrals}', [CentralsController::class, 'restore'])->name('Centrals.restore')->middleware('auth', 'checkverified', 'checkstatus');

