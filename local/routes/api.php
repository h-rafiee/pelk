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

Route::post('get_access',function(Request $request){

    $http = new GuzzleHttp\Client;

    $response = $http->post(url('oauth/token'), [
        'form_params' => [
            'grant_type'=>'client_credentials',
            'client_id' => $request->get('client_id'),
            'client_secret' => $request->get('client_secret'),
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

Route::post('authentication',function(Request $request){
    $http = new GuzzleHttp\Client;

    $response = $http->post(url('oauth/token'), [
        'form_params' => [
            'grant_type' => 'password',
            'client_id' => $request->get('client_id'),
            'client_secret' => $request->get('client_secret'),
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'scope' => '',
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

Route::post('refresh_token',function(Request $request){
    $http = new GuzzleHttp\Client;

    $response = $http->post(url('oauth/token'), [
        'form_params' => [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->get('refresh_token'),
            'client_id' => $request->get('client_id'),
            'client_secret' => $request->get('client_secret'),
            'scope' => '',
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});


Route::group(['namespace'=>'Api'],function(){

    Route::group(['middleware'=>'auth:api'],function(){

        Route::get('user','UserController@user');
        Route::get('user/orders','UserController@orders');
        Route::post('user/update','UserController@update');

        Route::get('add_bookshelf/demo/book/{id}','BookController@addToBookshelfDemo');
        Route::get('add_bookshelf/free/book/{id}','BookController@addToBookshelfFree');
        Route::get('add_bookshelf/demo/magazine/{id}','MagazineController@addToBookshelfDemo');
        Route::get('add_bookshelf/free/magazine/{id}','MagazineController@addToBookshelfFree');

        Route::get('download/book/{id}/{demo?}','DownloadController@book');
        Route::get('download/magazine/{id}/{demo?}','DownloadController@magazine');

        Route::post('order','OrderController@order');
        Route::get('bill/{code}','OrderController@bill');
        Route::get('payments','OrderController@payments');
    });

    Route::group(['middleware'=>'client_credentials'],function(){

        Route::get('home','ApiController@home');
        Route::post('sign_up','ApiController@sign_up');
        Route::get('categories','ApiController@categories');
        Route::get('publication/{id}/{type?}/{page?}','ApiController@publication');
        Route::get('writer/{id}/{page?}','ApiController@writer');
        Route::get('translator/{id}/{page?}','ApiController@translator');

        Route::get('search/{page?}','ApiController@search');

        Route::get('book/{id}','BookController@book');
        Route::get('books/{page?}/{orderBy?}/{order?}','BookController@books');
        Route::get('books_by_category/{category_id}/{page?}/{orderBy?}/{order?}','BookController@booksByCategory');
        Route::get('books_by_tag/{tag_id}/{page?}/{orderBy?}/{order?}','BookController@booksByTag');

        Route::get('magazine/{id}','MagazineController@magazine');
        Route::get('magazines/{page?}/{orderBy?}/{order?}','MagazineController@magazines');
        Route::get('magazines_by_category/{category_id}/{page?}/{orderBy?}/{order?}','MagazineController@magazinesByCategory');
        Route::get('magazines_by_tag/{tag_id}/{page?}/{orderBy?}/{order?}','MagazineController@magazinesByTag');
    });

});