<?php

Auth::routes();

Route::get('token', 'TestController@getToken');

Route::get('{any}', 'AppController')
	 ->where('any', '.*')
	 ->name('app');
