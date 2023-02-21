<?php

use App\Http\Controllers\Api\RolesAPIController;
use App\Http\Controllers\Api\UsersAPIController;
use App\Http\Controllers\Auth\PaymentsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Auth;
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
    Route::get('/user/login', 'login');
    Route::post('/user/login', 'login')->name('user.login');
    Route::get('/user/logout', 'logout')->name('user.logout');
    Route::get('/user/registration', 'registration');
    Route::post('/user/registration', 'registration')->name('user.registration');
});

Route::get('/users', [UsersController::class, 'index'])->name('users');
Route::get('/roles', [RolesController::class, 'index'])->name('roles');
Route::get('/payments', [PaymentsController::class, 'index'])->name('payments');


Route::get('/api/users/get', [UsersAPIController::class, 'getUsers']);
Route::post('/api/user/create', [UsersAPIController::class, 'userCreate']);
Route::post('/api/user/delete', [UsersAPIController::class, 'userDelete']);
Route::post('/api/user/update', [UsersAPIController::class, 'userUpdate']);
Route::post('/api/user/phone/add', [UsersAPIController::class, 'addUserPhone']);
Route::post('/api/user/phone/change', [UsersAPIController::class, 'changeUserPhone']);
Route::post('/api/user/phone/remove', [UsersAPIController::class, 'removeUserPhone']);
Route::post('/api/user/payment/create', [UsersAPIController::class, 'userPaymentCreate']);
Route::post('/api/user/payment/change', [UsersAPIController::class, 'changeUserPayment']);
Route::post('/api/user/payment/remove', [UsersAPIController::class, 'removeUserPayment']);

Route::post('/api/roles/roles_list', [RolesAPIController::class, 'changeRoles']);
Route::post('/api/roles/users_list', [RolesAPIController::class, 'changeUsers']);
Route::post('/api/roles/payments_list', [RolesAPIController::class, 'changePayments']);
Route::post('/api/roles/delete', [RolesAPIController::class, 'roleDelete']);
Route::post('/api/roles/create', [RolesAPIController::class, 'roleCreate']);
Route::post('/api/roles/update', [RolesAPIController::class, 'roleUpdate']);
