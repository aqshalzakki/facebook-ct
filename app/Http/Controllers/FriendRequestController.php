<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use App\Http\Resources\Friend as FriendResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\UserNotFoundException;

class FriendRequestController extends Controller
{
    public function store()
    {
		$data = request()->validate([
			'friend_id' => 'required'
		]);

		try {
			User::findOrFail($data['friend_id'])
				->friends()
				->attach(auth()->user(), [
					'created_at' => now(),
					'updated_at' => now(),
				]);

		} catch(ModelNotFoundException $e) {
			throw new UserNotFoundException();
		}

		return new FriendResource(
			Friend::where('user_id', auth()->id())
				->where('friend_id', $data['friend_id'])
				->first()
		);
    }
}
