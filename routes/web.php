<?php

use App\Http\Controllers\Api\RolesAPIController;
use App\Http\Controllers\Api\UsersAPIController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', IndexController::class)->name('index');

Route::controller(UserAuthController::class)->group(function () {
    Route::get('/auth/login', 'login');
    Route::post('/auth/login', 'login')->name('auth.login');
    Route::get('/auth/logout', 'logout')->name('auth.logout');
    Route::get('/auth/registration', 'registration');
    Route::post('/auth/registration', 'registration')->name('auth.registration');
});

Route::get('/user/{id}', UserController::class)->name('user');
Route::get('/users', [UsersController::class, 'index'])->name('users');
Route::get('/roles', [RolesController::class, 'index'])->name('roles');
Route::get('/payments', [PaymentsController::class, 'index'])->name('payments');

Route::get('/api/users/get', [UsersAPIController::class, 'getUsers']);
Route::post('/api/user/create', [UsersAPIController::class, 'userCreate']);
Route::post('/api/user/delete', [UsersAPIController::class, 'userDelete']);
Route::post('/api/user/update', [UsersAPIController::class, 'userUpdate']);
Route::post('/api/user/payment/create', [UsersAPIController::class, 'userPaymentCreate']);
Route::post('/api/user/payment/update', [UsersAPIController::class, 'userPaymentUpdate']);
Route::post('/api/user/payment/delete', [UsersAPIController::class, 'userPaymentDelete']);

Route::post('/api/roles/roles_list', [RolesAPIController::class, 'changeRoles']);
Route::post('/api/roles/users_list', [RolesAPIController::class, 'changeUsers']);
Route::post('/api/roles/payments_list', [RolesAPIController::class, 'changePayments']);
Route::post('/api/roles/delete', [RolesAPIController::class, 'roleDelete']);
Route::post('/api/roles/create', [RolesAPIController::class, 'roleCreate']);
Route::post('/api/roles/update', [RolesAPIController::class, 'roleUpdate']);
Route::post('/api/roles/set_default', [RolesAPIController::class, 'roleSetDefault']);

Route::get('/clear-cache', function() {
    $configCache = Artisan::call('config:cache');
    $clearCache = Artisan::call('cache:clear');
    $clearView = Artisan::call('view:clear');
});
