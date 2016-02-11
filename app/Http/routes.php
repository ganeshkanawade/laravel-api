<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['domain' => '{account}.laravelapi.com','middleware'=>['domain.routes','api']], function($account) {
	
	Route::get('/', function ($account) {
		return $account; 
	});
	
	Route::get('/posts', function () {
		$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvbGFyYXZlbGFwaVwvcHVibGljXC9hcGlcL3YxXC9hdXRoZW50aWNhdGUiLCJpYXQiOjE0NTUxMTI0NzMsImV4cCI6MTQ1NTExNjA3MywibmJmIjoxNDU1MTEyNDczLCJqdGkiOiJhNTU1YTQ1NGM4NDdiNzE2ZTFkOGFmYTkxYjc1ZmY3YSJ9.o1qJIL_Op5yC9oqVZsVodAf1udPBbxloQlOG8jMzxHU';
		$request = Request::create('api/v1/posts', 'GET',['token'=>$token]);
		$response = Route::dispatch($request)->getData();
	   
		$result =  json_decode(json_encode($response),true);
		echo '<pre>';
		print_r($result);
		////echo json_last_error();
		//
		//return $result;
	});

});

Route::group(['prefix' => 'api/v1', 'domain' => '{account}.laravelapi.com','middleware'=>['domain.routes','api','cors','api.jwt']], function($account) {

	Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
    Route::get('refresh_token', 'AuthenticateController@refresh_token');
    
    // Api calls
    
    Route::resource('posts', 'Api\V1\postsController');
});




Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
