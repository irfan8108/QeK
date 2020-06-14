<?php

/*
|--------------------------------------------------------------------------
| Application Routes for QeK
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/test', 'Controller@test');

$router->post('/login', 'LoginController@login');
$router->post('/register', 'UserController@register');

// $router->get('/users', ['middleware' => 'auth', 'uses' =>  'UserController@getUsers']);

$router->group(['middleware' => 'auth'], function () use ($router) {
	
	// USER ROUTES
	$router->get('/users', ['uses' =>  'UserController@getUsers']);

	// ADMIN ROUTES
	$router->post('/create/{modelName}', ['uses' =>  'AdminController@create']);
	$router->post('/update/{modelName}', ['uses' =>  'AdminController@modify']);
	$router->post('/destroy/{modelName}', ['uses' =>  'AdminController@remove']);
	$router->post('/restore/{modelName}', ['uses' =>  'AdminController@restore']);
});


// PUBLIC ROUTES
$router->get('/quran[/{cmd}]', 'QeKController@quran');


// WEB ROUTES
$router->get('/', function () use ($router) {
	// return $router->app->version();
	return view('doc.index');
});
$router->get('/developer/doc', function () {
	return view('doc.apis');
});