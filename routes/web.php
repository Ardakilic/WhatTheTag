<?php

Auth::routes();
//Standard logout is post, so we make a GET one
Route::get('/logout', 'Auth\LoginController@logout');

App::singleton('s3', function () {
    return Storage::disk('s3')->getDriver();
});

Route::get('/', 'PhotoController@getIndex');
Route::get('home', 'HomeController@index');

Route::get('recents', 'PhotoController@getRecents');
Route::get('search', 'PhotoController@getSearch');

/*Route::controllers([
    'photo' => 'PhotoController',
    'admin/users' => 'Admin\UserController',
    'admin/photos' => 'Admin\PhotoController',
]);*/

Route::group(['prefix' => 'photo'], function(){
    $c = 'PhotoController';
    Route::get('/', $c.'@getIndex');
    Route::get('tagged/{slug}', $c.'@getTagged');
    Route::get('user/{slug}', $c.'@getUser');
    Route::get('detail/{slug}', $c.'@getDetail');
    Route::get('new', $c.'@getNew');
    Route::post('new', $c.'@postNew');
});

Route::group(['prefix' => 'admin/users'], function (){
    $c = 'Admin\UserController';
    Route::get('/', $c.'@getIndex');
    Route::get('grid', $c.'@getGrid');
    Route::get('new', $c.'@getew');
    Route::post('new', $c.'@postNew');
    Route::get('edit/{id}', $c.'@getEdit');
    Route::post('edit/{id}', $c.'@postEdit');
    Route::get('delete/{id}', $c.'@getDelete');
});

Route::group(['prefix' => 'admin/photos'], function (){
    $c = 'Admin\PhotoController';
    Route::get('/', $c.'@getIndex');
    Route::get('grid', $c.'@getGrid');
    /*Route::get('new', $c.'@getew');
    Route::post('new', $c.'@postNew');*/
    Route::get('edit/{id}', $c.'@getEdit');
    Route::post('edit/{id}', $c.'@postEdit');
    Route::get('delete/{id}', $c.'@getDelete');
});