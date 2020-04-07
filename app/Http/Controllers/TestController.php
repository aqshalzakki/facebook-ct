<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function getToken()
    {
    	return [
    		'foo' => 'bar',
    		'token' => 'random token',
    	];
    }
}
