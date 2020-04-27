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
        Route::post('register', 'AuthController@register');
        Route::get('me', 'AuthController@me');
    });
});
