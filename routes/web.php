<?php

Auth::routes();

Route::get('token', 'TestController@getToken');
Route::get('username', 'TestController@getUsername');
Route::get('role-id', 'TestController@getRoleId');

Route::get('{any}', 'AppController')
	 ->where('any', '.*')
	 ->name('app');
