<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Friend as FriendResource;
use App\Friend;

class FriendRequestResponseController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'user_id' => 'required',
            'status' => 'required',
        ]);

        $friendRequest = Friend::where('user_id', $data['user_id'])
            ->where('friend_id', auth()->id())
            ->firstOrFail();

        $friendRequest->update(array_merge($data, [
            'confirmed_at' => now()
        ]));

        return new FriendResource($friendRequest);
    }
}
