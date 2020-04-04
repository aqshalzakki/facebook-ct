<?php

Auth::routes();

Route::get('{any}', 'AppController')
	 ->where('any', '.*')
	 ->name('app');
