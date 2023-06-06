<?php


use App\Http\Controllers\dashboard\HomeController;
use App\Http\Controllers\dashboard\RoleController;
use App\Http\Controllers\dashboard\UsersController;
use App\Http\Controllers\dashboard\CentralsController;
use App\Http\Controllers\Dashboard\CommentsController;
use App\Http\Controllers\Dashboard\CompoundsController;
use App\Http\Controllers\dashboard\TasksController;
use App\Http\Controllers\dashboard\TechController;



use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['prefix' => 'dashboard', 'middleware' => ['role:superadministrator|administrator']], function () {

    // admin users routes
    Route::resource('users', UsersController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('users/export/', [UsersController::class, 'export'])->name('users.export')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-users', [UsersController::class, 'trashed'])->name('users.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-users/{user}', [UsersController::class, 'restore'])->name('users.restore')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/activate-users/{user}', [UsersController::class, 'activate'])->name('users.activate')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/block-users/{user}', [UsersController::class, 'block'])->name('users.block')->middleware('auth', 'checkverified', 'checkstatus');
    Route::post('/add-bonus/{user}', [UsersController::class, 'bonus'])->name('users.bonus')->middleware('auth', 'checkverified', 'checkstatus');


    // roles routes
    Route::resource('roles',  RoleController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-roles', [RoleController::class, 'trashed'])->name('roles.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-roles/{role}', [RoleController::class, 'restore'])->name('roles.restore')->middleware('auth', 'checkverified', 'checkstatus');

    // Centrals routes
    Route::resource('centrals', CentralsController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-centrals', [CentralsController::class, 'trashed'])->name('centrals.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-centrals/{central}', [CentralsController::class, 'restore'])->name('centrals.restore')->middleware('auth', 'checkverified', 'checkstatus');

    // tasks route
    Route::resource('tasks', TasksController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-tasks', [TasksController::class, 'trashed'])->name('tasks.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-tasks/{task}', [TasksController::class, 'restore'])->name('tasks.restore')->middleware('auth', 'checkverified', 'checkstatus');

    // compounds route
    Route::resource('compounds', CompoundsController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-compounds', [CompoundsController::class, 'trashed'])->name('compounds.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-compounds/{compound}', [CompoundsController::class, 'restore'])->name('compounds.restore')->middleware('auth', 'checkverified', 'checkstatus');

    // comments route
    Route::resource('comments', CommentsController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-comments', [CommentsController::class, 'trashed'])->name('comments.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-comments/{comment}', [CommentsController::class, 'restore'])->name('comments.restore')->middleware('auth', 'checkverified', 'checkstatus');

    // import & export routes
    Route::post('tasks/import/', [TasksController::class, 'import'])->name('tasks.import')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('tasks/export/', [TasksController::class, 'export'])->name('tasks.export');

    // bulk  action
    Route::post('/tasks/bulk-action', 'TasksController@bulkAction')->name('tasks.bulk-action');

});
