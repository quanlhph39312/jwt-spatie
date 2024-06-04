<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [AccountController::class, 'login']);
Route::post('register', [AccountController::class, 'register']);
Route::get('verifyEmail/{email}', [AccountController::class, 'verifyEmail'])->name('verifyEmail');
Route::group(['middleware' => ["auth:api"]], function ($router) {
    Route::get('profile', [AccountController::class, 'profile']);
    Route::post('profile', [AccountController::class, 'editProfile']);
    Route::post('refresh', [AccountController::class, 'refreshToken']);
    Route::post('logout', [AccountController::class, 'logout']);
    Route::apiResource('user', UserController::class);
});
Route::get('hasRole/{id}', [PermissionController::class, 'hasRole']);
Route::get('/hasPermission/{id}', [PermissionController::class, 'hasPermission']);
Route::put('hasRole/{id}', [PermissionController::class, 'updateRole']);
Route::put('hasPermission/{id}', [PermissionController::class, 'updatePermission']);
Route::post('/createRole', [PermissionController::class, 'createRole']);
Route::post('/createPermission', [PermissionController::class, 'createPermission']);
Route::put('/assignPermissionsToRole/{roleId}', [PermissionController::class, 'assignPermissionsToRole']);
Route::get('/permissions', [PermissionController::class, 'getPermissions']);
