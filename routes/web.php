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

    //create a job post
    Route::get('job/create', 'JobController@create');
    Route::post('job/', 'JobController@store');

    //edit a job post
    Route::get('job/{job_id}/edit', 'JobController@edit');
    Route::put('job/{job_id}', 'JobController@update');

    //delete a job
    Route::delete('/job/{job_id}', 'JobController@destroy');

    //employer accept a job
    Route::put('job/{job_id}/accept', 'JobController@employerAccept');

    //employer cancel a job
    Route::put('job/{job_id}/employer-cancel', 'JobController@employerCancel');

    //jobseeker cancel a job
    Route::put('job/{job_id}/jobseeker-cancel', 'JobController@jobseekerCancel');

    //employer pick a jobseeker
    Route::put('job/{job_id}/employer-pick', 'JobController@employerPick');


    //create job application
    Route::get('/job-application/create', 'JobApplicationController@create');
    Route::post('/job-application', 'JobApplicationController@store');

    //edit job application
    Route::get('job-application/{job_id}/edit', 'JobApplicationController@edit');
    Route::put('job-application/{job_id}', 'JobApplicationController@update');

    //delete job application
    Route::delete('/job-application/{job_id}', 'JobApplicationController@destroy');
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