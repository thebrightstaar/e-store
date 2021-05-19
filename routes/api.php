<?php

namespace App\Http\Controllers\API;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');
Route::post('forgot', 'API\AuthController@Forgot');
Route::post('reset', 'API\AuthController@passwordReset');
Route::post('logout', 'API\AuthController@logout')->middleware('auth:api');
Route::put('update', 'API\AuthController@updateUserInformation')->middleware('auth:api');
Route::post('verify', 'API\AuthController@emailVerify');
Route::post('resend', 'API\AuthController@resendCode');

Route::get('faq', 'API\FAQController@index');
Route::post('add/question', 'API\FAQController@store')->middleware('auth:api');
Route::put('update/question/{id}', 'API\FAQController@update')->middleware('auth:api');
Route::delete('delete/question/{id}', 'API\FAQController@delete')->middleware('auth:api');
