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
Route::get('images/user', 'UserController@userProfileImage');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ['jwt.verify:1']], function() {
    Route::get('profile', 'UserController@getAuthenticatedUser');
});

/* User Details API */
Route::get('details/with_user={user_id}', 'UserDetailsController@index');
Route::post('add/details/{id}', 'UserDetailsController@store');
Route::put('update/details/{id}', 'UserDetailsController@update');

/* Page API */
Route::get('pages', 'PagesController@index');
Route::get('page/{id}', 'PagesController@show');
Route::post('add/page', 'PagesController@store');
Route::put('update/page/{id}', 'PagesController@update');
Route::delete('delete/page/{id}', 'PagesController@destroy');

/* Cities API */
Route::get('cities', 'CitiesController@index');
Route::get('cities/{id}', 'CitiesController@show');
Route::get('images/cities', 'CitiesController@cityCoverImage');
Route::post('add/city', 'CitiesController@store');
Route::put('update/city/{id}', 'CitiesController@update');
Route::delete('delete/city/{id}', 'CitiesController@destroy');

/* Categories API belong to City */
Route::get('categories/with_city={city_id}', 'CategoryController@index');
Route::get('categories/show/{id}', 'CategoryController@show');
Route::post('add/category/{id}', 'CategoryController@store');
Route::put('update/category/{id}', 'CategoryController@update');
Route::delete('delete/category/{id}', 'CategoryController@destroy');
