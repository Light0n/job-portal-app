<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/jobs', 'JobController@index')->name('jobs');
Route::get('/jobs/{job_id}', 'JobController@show');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/user/{user_id}', 'UserController@show');
});

//show all category
Route::get('/category', 'CategoryController@index');
//create a category
Route::get('/category/create', 'CategoryController@create');
Route::post('/category', 'CategoryController@store');

//edit a category
Route::get('/category/{category_id}/edit', 'CategoryController@edit');
Route::put('/category/{category_id}', 'CategoryController@update');

//delete a category
Route::delete('/category/{category_id}', 'CategoryController@destroy');

