<?php

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	
	'photo'		=> 'PhotoController',
	
	'auth'		=> 'Auth\AuthController',
	'password' 	=> 'Auth\PasswordController',
]);

Route::controller('admin/users', 'Admin\UserController');