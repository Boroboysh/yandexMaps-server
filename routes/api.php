<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PointController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->controller(LoginController::class)->group(function () {
    Route::post('login', 'authenticate');
    Route::post('register', 'register');
    Route::get('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('check', 'status');
    Route::get('user', 'getUserInfo');
});

Route::prefix('point')->controller(PointController::class)->group(function () {
    Route::get('list', 'getPointList');
    Route::post('new', 'newPoint');
    Route::patch('update/{id}', 'updatePoint');
    Route::delete('delete/{id}', 'deletePoint');
});

