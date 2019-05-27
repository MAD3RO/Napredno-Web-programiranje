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

Route::get('/home', 'HomeController@index');

Route::post('/home', 'UserController@setUserRole');

Route::post('/editUser', 'UserController@editUserRole');

Route::get('/dodajRad', 'TaskController@otvoriIzbornik');

Route::post('/dodajRad', 'TaskController@dodajRad');

Route::post('/english', 'HomeController@promijeniJezikNaEng');

Route::post('/croatian', 'HomeController@promijeniJezikNaHr');

Route::post('/prijava', 'UserController@prijaviSe');

Route::get('/prihvacanje', 'TaskController@prihvatiStudenta');

Route::post('/prihvacanje/potvrda', 'UserController@potvrdiStudenta');
