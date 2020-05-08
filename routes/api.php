<?php

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

// api/v1
Route::group(['prefix' => 'v1', 'namespace' => 'Api\v1'], function () {

    // api/v1/auth
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::get('me', 'AuthController@me');
    });

    // api/v1/admin
    Route::prefix('admin')->group(function () {
        Route::post('/', 'AdminController@store');
        Route::post('update/{admin}', 'AdminController@update');
        Route::post('update-password', 'AdminController@updatePassword');
    });

    // api/v1/admin-rule
    Route::prefix('admin-rule')->group(function () {
        Route::post('/', 'AdminRuleController@set');
    });

    // api/v1/info-buku
    Route::prefix('info-buku')->group(function () {
        Route::post('/', 'InfoBukuController@store');
    });

    // api/v1/penerbit-buku
    Route::prefix('penerbit-buku')->group(function () {
        Route::get('/', 'PenerbitBukuController@index');
    });
});
