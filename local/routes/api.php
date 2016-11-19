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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('getToken',function(Request $request){
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

Route::get('hello',function(){
    echo "Hello Api";
    return;
});
