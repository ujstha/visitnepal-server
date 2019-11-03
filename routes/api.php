<?php

use Illuminate\Http\Request;

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

/* Cities API */
Route::get('cities', 'CitiesController@index');
Route::get('cities/{id}', 'CitiesController@show');
Route::post('add/city', 'CitiesController@store');
Route::put('update/city/{id}', 'CitiesController@update');
Route::delete('delete/city/{id}', 'CitiesController@destroy');

/* Users API */
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::get('profile', 'UserController@getAuthenticatedUser');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
