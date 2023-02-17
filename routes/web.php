<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\IndexController;
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

Route::get('/admin', [AdminController::class, 'index'])->name('admin');


Route::get('/api/users/get', [UsersController::class, 'getUsers']);
Route::post('/api/user/add', [UsersController::class, 'addUser']);
Route::post('/api/user/remove', [UsersController::class, 'removeUser']);
Route::post('/api/user/name/change', [UsersController::class, 'changeUserName']);
Route::post('/api/user/phone/add', [UsersController::class, 'addUserPhone']);
Route::post('/api/user/phone/change', [UsersController::class, 'changeUserPhone']);
Route::post('/api/user/phone/remove', [UsersController::class, 'removeUserPhone']);
Route::post('/api/user/payment/add', [UsersController::class, 'addUserPayment']);
Route::post('/api/user/payment/change', [UsersController::class, 'changeUserPayment']);
Route::post('/api/user/payment/remove', [UsersController::class, 'removeUserPayment']);
