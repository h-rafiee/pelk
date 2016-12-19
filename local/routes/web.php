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
    Route::resource('web/sliders','PromoteController');


    // Web Template
    Route::get('web/template','AdminController@getWebTemplate');
    Route::post('web/template','AdminController@postWebTemplate');

    // Mob Template
    Route::get('mobile/sliders','AdminController@getMobileSliders');
    Route::post('mobile/sliders','AdminController@postMobileSliders');
    Route::get('mobile/template','AdminController@getMobileTemplate');
    Route::post('mobile/template','AdminController@postMobileTemplate');

    // Ajax
    Route::group(['middleware' => 'ajax','prefix'=>'ajax'],function(){
        Route::post('upload','AjaxController@upload');
        Route::get('categories/{banner?}','AjaxController@categories');
        Route::get('tags/{banner?}','AjaxController@tags');
        Route::get('publications/{banner?}','AjaxController@publications');
        Route::get('writers/{banner?}','AjaxController@writers');
        Route::get('translators/{banner?}','AjaxController@translators');
        Route::get('books/{banner?}','AjaxController@writers');
        Route::get('magazines/{banner?}','AjaxController@translators');
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
    Route::get('search','ClientController@getSearch');

    Route::get('books/categories/{title}','BookController@booksByCategory');
    Route::get('books/tags/{title}','BookController@booksByTag');
    Route::get('books/publications/{title}','BookController@booksByPublication');
    Route::get('books/writers/{title}','BookController@booksByWriter');
    Route::get('books/translators/{title}','BookController@booksByTranslator');

    Route::get('magazines/categories/{title}','MagazineController@magazinesByCategory');
    Route::get('magazines/tags/{title}','MagazineController@magazinesByTag');
    Route::get('magazines/publications/{title}','MagazineController@magazinesByPublication');

    Route::get('book/{slug}/{title?}','BookController@book');
    Route::get('magazine/{slug}/{title?}','MagazineController@magazine');

    Route::group(['middleware' => 'ajax'],function(){
        Route::post('search/books','AjaxController@searchBooks');
        Route::post('search/magazines','AjaxController@searchMagazines');
    });
});