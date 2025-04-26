<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Route to clear cache and optimize
Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
    return response()->json(['message' => 'Optimize clear completed']);
});

// Route to run migrations
Route::get('/migrate', function () {
    Artisan::call('migrate');
    return response()->json(['message' => 'Migrations completed']);
});

// Route to run fresh migrations with seeding
Route::get('/migrate-fresh-seed', function () {
    Artisan::call('migrate:fresh --seed');
    return response()->json(['message' => 'Fresh migrations and seeding completed']);
});
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return response()->json(['message' => 'Storage linked successfully .']);
});
Route::get('/telescope-install', function () {
    Artisan::call('telescope:install');
    return response()->json(['message' => 'Telescope installed successfully .']);
});

Route::get('/composer-install', function () {
    // Run the composer install command
    $output = shell_exec('composer install --ignore-platform-reqs 2>&1');

    return response()->json([
        'message' => 'Composer install completed',
        'output' => $output,
    ]);
});
Route::get('send',[\App\Http\Controllers\Api\V1\User\UserController::class,'sendNotifications']);
