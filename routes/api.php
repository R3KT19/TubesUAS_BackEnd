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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('email/verify/{id}/{hash}', 'Api\EmailVerificationController@__invoke')->name('verification.verify');

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');
Route::get('user', 'Api\AuthController@index');
Route::group(['middleware' => 'auth:api'], function()
{
    Route::get('service', 'Api\ServiceController@index');
    Route::get('service/{id}', 'Api\ServiceController@show');
    Route::post('service', 'Api\ServiceController@store');
    Route::put('service/{id}', 'Api\ServiceController@update');
    Route::delete('service/{id}', 'Api\ServiceController@destroy');

    Route::get('car', 'Api\CarController@index');
    Route::get('car/{id}', 'Api\CarController@show');
    Route::post('car', 'Api\CarController@store');
    Route::put('car/{id}', 'Api\CarController@update');
    Route::delete('car/{id}', 'Api\CarController@destroy');

    Route::get('personal', 'Api\PersonalServiceController@index');
    Route::get('personal/{id}', 'Api\PersonalServiceController@show');
    Route::post('personal', 'Api\PersonalServiceController@store');
    Route::put('personal/{id}', 'Api\PersonalServiceController@update');
    Route::delete('personal/{id}', 'Api\PersonalServiceController@destroy');

    Route::put('user/{id}', 'Api\AuthController@update');
    Route::delete('user/{id}', 'Api\AuthController@destroy');
    Route::get('user/{id}', 'Api\AuthController@show');
});