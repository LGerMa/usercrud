<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/key', function(){
	return str_random(32);
});

$router->post('/login', ['uses' => 'UserController@getToken']);


$router->group(['middleware' => ['auth']], function() use ($router) {
	$router->get('/users', ['uses' => 'UserController@index']);   
	$router->post('/user', ['uses' => 'UserController@createUser']);
	$router->get('/user/{id}', ['uses' => 'UserController@findUser']);
	$router->put('/user/{id}', ['uses' => 'UserController@updateUser']);
	$router->delete('/user/{id}', ['uses' => 'UserController@deleteUser']);
});


