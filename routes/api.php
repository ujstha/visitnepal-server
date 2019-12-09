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
Route::get('users/all', 'UserController@getAllUser');
Route::get('users/count', 'UserController@count');
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::post('reset/{email}', 'UserController@resetPassword');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => ['jwt.verify:0,1']], function () {
    Route::get('profile', 'UserController@getAuthenticatedUser');
    Route::post('user/add/details/{id}', 'UserDetailsController@store');
    Route::post('user/update/details/{id}', 'UserDetailsController@update');
    Route::post('user/add/images/with_user={user_id}', 'UserImagesController@store');
    Route::post('user/update/images/with_id={id}/with_user={user_id}', 'UserImagesController@update');
});

/* User Details API */
Route::get('user/details/all', 'UserDetailsController@index');
Route::get('user/details/with_user={user_id}', 'UserDetailsController@getDetailsByUserId');

/* User Images API */
Route::get('user/images/all', 'UserImagesController@index');
Route::get('user/images/with_user={user_id}', 'UserImagesController@getImagesByUserId');

Route::group(['middleware' => ['jwt.verify:1']], function () {
    Route::post('add/page', 'PagesController@store');
    Route::post('update/page/{id}', 'PagesController@update');
    Route::delete('delete/page/{id}', 'PagesController@destroy');
});

/* Page API */
Route::get('pages', 'PagesController@index');
Route::get('page/{id}', 'PagesController@show');

/* Cities API */
Route::get('cities', 'CitiesController@index');
Route::get('cities/{id}', 'CitiesController@show');
Route::post('add/city', 'CitiesController@store');
Route::post('update/city/{id}', 'CitiesController@update');
Route::delete('delete/city/{id}', 'CitiesController@destroy');

/* Categories API belong to City */
Route::get('categories/all', 'CategoryController@index');
Route::get('categories/with_city={city_id}', 'CategoryController@getCategoryByCityId');
Route::get('categories/show/{id}', 'CategoryController@show');
Route::post('add/category/on_city={city_id}', 'CategoryController@store');
Route::post('update/category/with_id={id}', 'CategoryController@update');
Route::delete('delete/category/with_id={id}', 'CategoryController@destroy');

/* Cities Images API */
Route::get('city/images/all', 'CitiesImagesController@index');
Route::get('city/image/with_city={city_id}', 'CitiesImagesController@getImagesByCityId');
Route::post('city/add/image/{city_id}', 'CitiesImagesController@store');
Route::post('city/update/image/with_id={id}/with_city={city_id}', 'CitiesImagesController@update');

/* Comments API */
Route::get('comments', 'CommentsController@index');
Route::get('comments/with_city_id={city_id}', 'CommentsController@getCommentByCityId');
Route::post('add/comment/on_city={city_id}/by_user={user_id}', 'CommentsController@store');
Route::post('update/comment/with_id={id}/by_user={user_id}', 'CommentsController@update');
Route::delete('delete/comment/with_id={id}/by_user={user_id}', 'CommentsController@destroy');

/* Ratings API */
Route::get('ratings', 'RatingsController@index');
Route::get('ratings/with_city_id={city_id}', 'RatingsController@getRatingByCityId');
Route::get('ratings/avg/with_city_id={city_id}', 'RatingsController@getAvgRatingByCityId');
Route::post('add/rating/on_city={city_id}/by_user={user_id}', 'RatingsController@store');
Route::post('update/rating/with_id={id}/by_user={user_id}', 'RatingsController@update');
Route::delete('delete/rating/with_id={id}/by_user={user_id}', 'RatingsController@destroy');

/* Slider API */
Route::get('slider', 'SliderController@index');
Route::post('add/slides', 'SliderController@store');
Route::post('update/slide/with_id={id}', 'SliderController@update');
Route::delete('delete/slide/with_id={id}', 'SliderController@destroy');

/* Count Visit, Comment, User */
Route::get('count/all', 'CountController@count');