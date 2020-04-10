<?php

namespace Tests\Feature;

use App\User;
use App\Friend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class FriendsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_send_a_friend_request()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $this->actingAs($user, 'api');

        $response = $this->post('/api/friend-request', [
            'user_id' => $anotherUser->id,
        ])->assertStatus(200);

        $friendRequest = Friend::first();

        $this->assertNotNull($friendRequest);
        $this->assertEquals($anotherUser->id, $friendRequest->friend_id);
        $this->assertEquals($user->id, $friendRequest->user_id);
    
        $response->assertJson([
            'data' => [
                'type' => 'friend_request',
                'friend_request_id' => $friendRequest->id,
                'attributes' => [
                    'confirmed_at' => null
                ]
            ],
            'links' => [
                'self' => url("/users/$anotherUser->id")
            ]
        ]);
    }

    /** @test */
    public function only_valid_user_can_be_friend_requested() 
    {   
        $id = 12345;

        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->post('/api/friend-request', [
            'user_id' => $id
        ]);

        $this->assertNull(Friend::first());
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                'status' => 404,
                'title' => 'User not Found!',
                'detail' => "Unable to locate the user with the given id of $id.",
            ]
        ]);
    }

    /** @test */
    public function friend_requests_can_be_accepted()
    {
        // Create dummy users
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        // authenticated as users api
        $this->actingAs($user, 'api');

        // make a friend request
        $this->post('/api/friend-request', [
            'user_id' => $anotherUser->id,
            ])->assertStatus(200);
            
        // accept a friend request as another user
        $response = $this->actingAs($anotherUser, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => $user->id,
                'status' => 1,
            ])->assertStatus(200);

        $friendRequest = Friend::first();
        
        // Asserting data
        $this->assertNotNull($friendRequest->confirmed_at);
        $this->assertInstanceOf(Carbon::class, $friendRequest->confirmed_at);
        $this->assertEquals(now()->startOfSecond(), $friendRequest->confirmed_at);
        $this->assertEquals(1, $friendRequest->status);
        $response->assertJson([
            'data' => [
                'type' => 'friend_request',
                'friend_request_id' => $friendRequest->id,
                'attributes' => [
                    'confirmed_at' => $friendRequest->confirmed_at
                        ->diffForHumans()
                ]
            ],
            'links' => [
                'self' => url("/users/$anotherUser->id")
            ]
        ]);
    }

    /** @test */
    public function only_valid_friend_requests_can_be_accepted()
    {
        $user_id = 3413;
        $anotherUser = factory(User::class)->create();

        // accept a friend request
        $response = $this->actingAs($anotherUser, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => $user_id,
                'status' => 1,
            ])->assertStatus(404);

        $this->assertNull(Friend::first());
        $response->assertJson([
            'errors' => [
                'status' => 404,
                'title' => 'Friend Request not Found!',
                'detail' => "Unable to locate the friend request with the given id of $user_id.",
            ]
        ]);
    }

    /** @test */
    public function only_the_recipient_can_accept_a_friend_request()
    {
        // Create dummy users
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();
        $thirdUser = factory(User::class)->create();

        // authenticated as users api
        $this->actingAs($user, 'api');

        // make a friend request
        $this->post('/api/friend-request', [
            'user_id' => $anotherUser->id,
        ])->assertStatus(200);
        
        // accept a friend request
        $response = $this->actingAs($thirdUser, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => $user->id,
                'status' => 1,
        ]);
        $response->assertStatus(404);

        $friendRequest = Friend::first();

        $this->assertNull($friendRequest->confirmed_at);
        $this->assertEquals(0, $friendRequest->status);

        $response->assertJson([
            'errors' => [
                'status' => 404,
                'title' => 'Friend Request not Found!',
                'detail' => "Unable to locate the friend request with the given id of $user->id.",
            ]
        ]);
    }
}
