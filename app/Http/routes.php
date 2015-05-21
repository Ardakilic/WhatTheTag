<?php

Route::get('/', 'PhotoController@getIndex');

Route::get('recents', 'PhotoController@getRecents');
Route::get('search', 'PhotoController@getSearch');

Route::get('home', 'HomeController@index');

Route::controllers([
	'photo'			=> 'PhotoController',
	
	'auth'			=> 'Auth\AuthController',
	'password'		=> 'Auth\PasswordController',
	
	'admin/users'	=> 'Admin\UserController',
	'admin/photos'	=> 'Admin\PhotoController',
]);