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

    // Books
    Route::resource('books','BookController');

    // Magazines
    Route::resource('magazines','MagazineController');

    // Promotes
    Route::resource('sliders','PromoteController');


    // Web Template
    Route::get('web/template','AdminController@getWebTemplate');
    Route::post('web/template','AdminController@postWebTemplate');

    // Ajax
    Route::group(['middleware' => 'ajax','prefix'=>'ajax'],function(){
        Route::post('upload','AjaxController@upload');
        Route::get('categories','AjaxController@categories');
        Route::get('tags','AjaxController@tags');
        Route::get('publications','AjaxController@publications');
        Route::get('writers','AjaxController@writers');
        Route::get('translators','AjaxController@translators');
    });

});
Route::get('/admin-login',['middleware' => 'admin.guest','uses'=>'Admin\AdminController@getLogin']);
Route::post('/admin-login',['middleware' => 'admin.guest','uses'=>'Admin\AdminController@postLogin']);



Route::group(['namespace'=>'Client'],function(){
    Route::get('/','ClientController@getHome');
    Route::get('intro','ClientController@getIntro');
    Route::get('categories','ClientController@getCategories');
    Route::get('login','ClientController@getLogin');
    Route::get('profile','ClientController@getProfile');

    Route::get('book','ClientController@getBook');
});