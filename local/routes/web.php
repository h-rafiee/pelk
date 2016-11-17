<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>'admin.auth'],function(){
    Route::get('/',function(){
        return view('admin.index');
    });
    Route::get('logout','AdminController@logout');

   // Administrators
    Route::resource('administrators','AdministratorController');

   // Users
    Route::resource('users','UserController');

    // Tags
    Route::resource('tags','TagController');

    // Categories
    Route::resource('categories','CategoryController');

    // Book
    Route::resource('books','BookController');

    // Book
    Route::resource('magazines','MagazineController');


});
Route::get('/admin-login',['middleware' => 'admin.guest','uses'=>'Admin\AdminController@getLogin']);
Route::post('/admin-login',['middleware' => 'admin.guest','uses'=>'Admin\AdminController@postLogin']);


Route::get('/', function () {
    return view('welcome');
});