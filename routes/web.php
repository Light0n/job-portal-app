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

//welcome page
Route::get('/', function () {return view('welcome');});

//login, register 
Auth::routes();

//user homepage
Route::get('/home', 'HomeController@index')->name('home');

//browse jobs
Route::get('/jobs', 'JobController@index')->name('jobs');

//show one job
Route::get('/jobs/{job_id}', 'JobController@show');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/user/{user_id}', 'UserController@show');//test

    //edit user + add skills
    Route::get('user/{user_id}/edit', 'UserController@edit');
    Route::put('user/{user_id}', 'UserController@update');
});



// CRUD CATEGORY BEGIN
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
// CRUD CATEGORY END

// CRUD SKILL BEGIN
//show all skill
Route::get('/skill', 'SkillController@index');
//create a skill
Route::get('/skill/create', 'SkillController@create');
Route::post('/skill', 'SkillController@store');
//edit a skill
Route::get('/skill/{skill_id}/edit', 'SkillController@edit');
Route::put('/skill/{skill_id}', 'SkillController@update');
//delete a skill
Route::delete('/skill/{skill_id}', 'SkillController@destroy');
// CRUD SKILL END