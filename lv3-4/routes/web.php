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

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::post('/createNew', 'HomeController@createNewProject');

Route::post('/finish', 'HomeController@finishProject');

Route::post('/edit', 'HomeController@editProject');

Route::post('/editProject', 'HomeController@editProjectOk');

Route::post('/delete', 'HomeController@deleteProject');

Route::get('/', 'HomeController@index');

Route::get('/create', 'CreateProjectController@index');

Route::get('/finished', 'HomeController@finishedProjects');

Route::get('/profile', 'HomeController@profile');