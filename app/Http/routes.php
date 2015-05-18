<?php

Route::get('/', 'PhotoController@getIndex');

Route::get('home', 'HomeController@index');

Route::controllers([
	
	'photo'		=> 'PhotoController',
	
	'auth'		=> 'Auth\AuthController',
	'password' 	=> 'Auth\PasswordController',
]);

Route::controller('admin/users', 'Admin\UserController');