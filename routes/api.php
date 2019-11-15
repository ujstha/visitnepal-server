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
Route::post('reset/{email}', 'UserController@resetPassword');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => ['jwt.verify:0,1']], function () {
    Route::get('profile', 'UserController@getAuthenticatedUser');
    Route::post('add/details/{id}', 'UserDetailsController@store');
    Route::put('update/details/{id}', 'UserDetailsController@update');
});

/* User Details API */
Route::get('user/details/with_user={user_id}', 'UserDetailsController@index');

Route::group(['middleware' => ['jwt.verify:1']], function () {
    Route::post('add/page', 'PagesController@store');
    Route::put('update/page/{id}', 'PagesController@update');
    Route::delete('delete/page/{id}', 'PagesController@destroy');
});

/* User Images API */
Route::get('user/image/with_user={user_id}', 'UserImagesController@index');
Route::post('user/add/image/with_user={user_id}', 'UserImagesController@store');
Route::put('user/update/image/{id}', 'UserImagesController@update');

/* Page API */
Route::get('pages', 'PagesController@index');
Route::get('page/{id}', 'PagesController@show');

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

/* Cities Images API */
Route::get('city/image/with_city={city_id}', 'CitiesImagesController@index');
Route::post('city/add/image/{city_id}', 'CitiesImagesController@store');

/* Comments API */
Route::get('comments', 'CommentsController@index');
Route::post('add/comment/on_city={city_id}/by_user={user_id}', 'CommentsController@store');
Route::put('update/comment/with_id={id}/by_user={user_id}', 'CommentsController@update');