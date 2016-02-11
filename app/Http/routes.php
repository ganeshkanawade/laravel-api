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
$domain = Config::get('constants.DOMAIN');

Route::group(['domain' => '{account}.'.$domain,'middleware'=>['domain.routes','api']], function($account) {
	
	//Route::get('/', function ($account) {
	//	return $account; 
	//});
	
	Route::get('/', function(){
		return redirect('/home');
	});
	
	Route::get('/posts', function ($account) {
		
		$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkb21haW4iOiJwdW5lLmxhcmF2ZWxhcGkuY29tIiwic3ViIjoxLCJpc3MiOiJodHRwOlwvXC9wdW5lLmxhcmF2ZWxhcGkuY29tXC9hcGlcL3YxXC9hdXRoZW50aWNhdGUiLCJpYXQiOjE0NTUxOTY3ODcsImV4cCI6MTQ1NTIwMDM4NywibmJmIjoxNDU1MTk2Nzg3LCJqdGkiOiJkNTk2ZTBlNjZjMDEwYTI0MWMyOWI5ZWE0ODY1M2RmNCJ9.q90We6ethCBFzuw-xGnRm8MCy73sQ0gZUhRNXQVC7ZE';
		$request = Request::create(Request::root().'/api/v1/posts', 'GET',['token'=>$token]);
		$response = Route::dispatch($request)->getData();
		$result =  json_decode(json_encode($response),true);
		echo '<pre>';
		print_r($result);
		//return $result;
	});

});

Route::group(['prefix' => 'api/v1', 'domain' => '{account}.'.$domain,'middleware'=>['domain.routes','api','cors','api.jwt']], function($account) {

	Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
    Route::get('refresh_token', 'AuthenticateController@refresh_token');
    
    // Api calls
    
    Route::resource('posts', 'Api\V1\postsController');
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
