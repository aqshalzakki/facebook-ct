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

    /** @test
     *  
     * A User Can Send A Friend Request
     * 
    */
    public function a_user_can_send_a_friend_request()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $this->actingAs($user, 'api');

        $response = $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id,
        ])->assertStatus(200);

        $friendRequest = Friend::first();

        $this->assertNotNull($friendRequest);
        $this->assertEquals($anotherUser->id, $friendRequest->friend_id);
        $this->assertEquals($user->id, $friendRequest->user_id);
    
        $response->assertExactJson([
            'data' => [
                'type' => 'friend_request',
                'friend_request_id' => $friendRequest->id,
                'attributes' => [
                    'status' => 0,
                    'confirmed_at' => null
                ]
            ],
            'links' => [
                'self' => url("/users/$anotherUser->id")
            ]
        ]);
    }

    /** @test 
     *
     * Only Valid User Can Be Friend Requested
     *  
    */
    public function only_valid_user_can_be_friend_requested() 
    {   
        $id = 12345;

        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->post('/api/friend-request', [
            'friend_id' => $id
        ]);

        $this->assertNull(Friend::first());
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                'status' => 404,
                'title' => 'User not Found!',
                'detail' => "Unable to locate the user with the given information.",
            ]
        ]);
    }

    /** @test 
     *
     * Friend Requests Can Be Accepted
     *  
    */
    public function friend_requests_can_be_accepted()
    {
        // Create dummy users
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        // authenticated as users api
        $this->actingAs($user, 'api');

        // make a friend request
        $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id,
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
                        ->diffForHumans(),
                    'friend_id' => $friendRequest->friend_id,
                    'user_id' => $friendRequest->user_id,
                ]
            ],
            'links' => [
                'self' => url("/users/$anotherUser->id")
            ]
        ]);
    }

    /** @test 
     *
     * Friend Requests Can Be Ignored
     *  
    */
    public function friend_requests_can_be_ignored()
    {
        $this->withoutExceptionHandling();

        // Create dummy users
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        // authenticated as users api
        $this->actingAs($user, 'api');

        // make a friend request
        $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id,
        ])->assertStatus(200);
            
        // accept a friend request as another user
        $response = $this->actingAs($anotherUser, 'api')
            ->delete('/api/friend-request-response/delete', [
                'user_id' => $user->id,
        ])->assertStatus(204);

        $friendRequest = Friend::first();
        
        // Asserting data
        $this->assertNull($friendRequest);
        $response->assertNoContent();
    }

    /** @test 
     *
     * Only Valid Friend Requests Can Be Accepted
     *  
    */
    public function only_valid_friend_requests_can_be_accepted()
    {
        $user_id = rand(1, 100);
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
                'detail' => "Unable to locate the friend request with the given information.",
            ]
        ]);
    }

    /** @test 
     *
     * Only Valid Friend Requests Can Beccepted
     *  
    */
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
            'friend_id' => $anotherUser->id,
        ])->assertStatus(200);
        
        // accept a friend request
        $response = $this->actingAs($thirdUser, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => $user->id,
                'status' => 1,
        ])->assertStatus(404);

        $friendRequest = Friend::first();

        $this->assertNull($friendRequest->confirmed_at);
        $this->assertEquals(0, $friendRequest->status);

        $response->assertJson([
            'errors' => [
                'status' => 404,
                'title' => 'Friend Request not Found!',
                'detail' => "Unable to locate the friend request with the given information.",
            ]
        ]);
    }

    /** @test 
     * 
     * Only The Recipient Can Ignore A Friend Request
     * 
    */
    public function only_the_recipient_can_ignore_a_friend_request()
    {
        // Create dummy users
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();
        $thirdUser = factory(User::class)->create();

        // authenticated as users api
        $this->actingAs($user, 'api');

        // make a friend request
        $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id,
        ])->assertStatus(200);
        
        // ignore a friend request
        $response = $this->actingAs($thirdUser, 'api')
            ->delete('/api/friend-request-response/delete', [
                'user_id' => $user->id
        ])->assertStatus(404);

        $friendRequest = Friend::first();

        $this->assertNull($friendRequest->confirmed_at);
        $this->assertEquals(0, $friendRequest->status);

        $response->assertJson([
            'errors' => [
                'status' => 404,
                'title' => 'Friend Request not Found!',
                'detail' => "Unable to locate the friend request with the given information.",
            ]
        ]);
    }

    /** @test 
     * 
     * A Friend Id Is Required For Friend Request
     * 
    */
    public function a_friend_id_is_required_for_friend_request()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')
            ->post('/api/friend-request', [
                'friend_id' => null,
            ])->assertStatus(422);
        
        $arrayResponse = $response->decodeResponseJson();

        $this->assertArrayHasKey('friend_id', $arrayResponse['errors']['meta']);

    }

    /** @test 
     * 
     * A User Id And Status Is Required For Friend Request Response
     * 
    */
    public function a_user_id_and_status_is_required_for_friend_request_response()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => null,
                'status' => null,
            ])->assertStatus(422);
        
        $arrayResponse = $response->decodeResponseJson();

        $this->assertArrayHasKey('user_id', $arrayResponse['errors']['meta']);    
        $this->assertArrayHasKey('status', $arrayResponse['errors']['meta']);    
    }

    /** @test 
     * 
     * A User Id Is Required For Ignoring Friend Request Response
     * 
    */
    public function a_user_id_is_required_for_ignoring_friend_request_response()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')
            ->delete('/api/friend-request-response/delete', [
                'user_id' => null
            ])->assertStatus(422);
        
        $arrayResponse = $response->decodeResponseJson();

        $this->assertArrayHasKey('user_id', $arrayResponse['errors']['meta']);
    }

    /** @test 
     * 
     * A Friendship Is Retrieved When Fetching The Profile
     * 
    */
    public function a_friendship_is_retrieved_when_fetching_the_profile()
    {
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $this->actingAs($user, 'api');
        
        $friendRequest = Friend::create([
            'user_id' => $user->id,
            'friend_id' => $anotherUser->id,
            'status' => 1,
            'confirmed_at' => now()->subDay()
        ]);

        $this->get("/api/users/$anotherUser->id")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type'    => 'users',
                    'attributes' => [
                        'friendship' => [
                            'data' => [
                                'friend_request_id' => $friendRequest->id,
                                'attributes' => [
                                    'confirmed_at' => '1 day ago'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test 
     * 
     * An Inverse Friendship Is Retrieved When Fetching The Profile
     * 
    */
    public function an_inverse_friendship_is_retrieved_when_fetching_the_profile()
    {
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $this->actingAs($user, 'api');
        
        $friendRequest = Friend::create([
            'friend_id' => $user->id,
            'user_id' => $anotherUser->id,
            'status' => 1,
            'confirmed_at' => now()->subDay()
        ]);

        $this->get("/api/users/$anotherUser->id")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type'    => 'users',
                    'attributes' => [
                        'friendship' => [
                            'data' => [
                                'friend_request_id' => $friendRequest->id,
                                'attributes' => [
                                    'confirmed_at' => '1 day ago'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
