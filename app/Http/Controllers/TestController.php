<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class TestController extends Controller
{
    public function getRoleId()
    {
    	return [
    		'role_id' => User::first()->role_id
    	];
    }
}
