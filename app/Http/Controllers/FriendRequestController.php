<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use App\Http\Resources\Friend as FriendResource;

class FriendRequestController extends Controller
{
    public function store()
    {
		$data = request()->validate([
			'friend_id' => 'required'
		]);

		User::find($data['friend_id'])
			->friends()
			->attach(auth()->user());

		return new FriendResource(
			Friend::where('user_id', auth()->id())
				->where('friend_id', $data['friend_id'])
				->first()
		);
    }
}
