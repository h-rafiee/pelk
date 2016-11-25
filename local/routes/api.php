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


Route::group(['namespace'=>'Api'],function(){

    Route::group(['middleware'=>'auth:api'],function(){
        // TODO edit profile
        Route::get('user',function(Request $request){
            return $request->user();
        });
    });

    Route::group(['middleware'=>'client_credentials'],function(){


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