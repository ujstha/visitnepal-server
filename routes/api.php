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

/* Users API */
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::get('profile', 'UserController@getAuthenticatedUser');
Route::get('images/user', 'UserController@userProfileImage');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/* Cities API */
Route::get('cities', 'CitiesController@index');
Route::get('cities/{id}', 'CitiesController@show');
Route::post('add/city', 'CitiesController@store');
Route::put('update/city/{id}', 'CitiesController@update');
Route::delete('delete/city/{id}', 'CitiesController@destroy');
Route::get('images/cities', 'CitiesController@cityCoverImage');

/* Categories API belong to City */
Route::post('add/category/{id}', 'CategoryController@store');
Route::get('categories/with_city={city_id}', 'CategoryController@index');
Route::get('categories/show/{id}', 'CategoryController@show');
Route::put('update/category/{id}', 'CategoryController@update');
Route::delete('delete/category/{id}', 'CategoryController@destroy');
