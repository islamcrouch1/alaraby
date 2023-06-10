<?php

use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\TechController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'user', 'middleware' => ['role:superadministrator|administrator']], function () {

    // home view route - dashboard
    Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth', 'checkverified', 'checkstatus');

    // user routes
    Route::get('edit', [ProfileController::class, 'edit'])->name('user.edit')->middleware('auth', 'checkverified', 'checkstatus');
    Route::post('update', [ProfileController::class, 'update'])->name('user.update')->middleware('auth', 'checkverified', 'checkstatus');

    // user notification routes
    Route::get('/notification/change', [UserNotificationsController::class, 'changeStatus'])->name('notifications.change')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/notifications', [UserNotificationsController::class, 'index'])->name('notifications.index')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/notifications/change/all', [UserNotificationsController::class, 'changeStatusAll'])->name('notifications.change.all')->middleware('auth', 'checkverified', 'checkstatus');

    // user mesaages
    Route::get('messages', [MessagesController::class, 'index'])->name('messages.index')->middleware('auth', 'checkverified', 'checkstatus');
    Route::post('messages/store', [MessagesController::class, 'store'])->name('messages.store')->middleware('auth', 'checkverified', 'checkstatus');

    // withdrawalw route
    Route::get('withdrawals', [WithdrawalsController::class, 'index'])->name('withdrawals.user.index')->middleware('auth', 'checkverified', 'checkstatus');
    Route::post('withdrawals/store', [WithdrawalsController::class, 'store'])->name('withdrawals.user.store')->middleware('auth', 'checkverified', 'checkstatus');

    // store information route
    Route::post('store/update', [ProfileController::class, 'updateStore'])->name('user.store.update')->middleware('auth', 'checkverified', 'checkstatus');
});



Route::group(['prefix' => 'tech'], function () {

    // home view route - dashboard
    Route::get('/', [TechController::class, 'index'])->name('tech.home')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/tech-tasks', [TechController::class, 'myTasks'])->name('tech.tasks')->middleware('auth', 'checkverified', 'checkstatus');



    Route::get('/tech-edit/{task}', [TechController::class, 'edit'])->name('tech.edit')->middleware('auth', 'checkverified', 'checkstatus');
    Route::post('/tech-update/{task}', [TechController::class, 'update'])->name('tech.update')->middleware('auth', 'checkverified', 'checkstatus');
});
