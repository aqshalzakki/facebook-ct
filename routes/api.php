<?php

// Route::middleware('auth:api')->group(function() {

	Route::get('/posts', 'PostController@index')->name('posts.index');
	Route::post('/posts', 'PostController@store')->name('posts.store');
// });
