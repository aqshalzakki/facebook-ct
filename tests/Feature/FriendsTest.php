<?php

namespace Tests\Feature;

use App\User;
use App\Friend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FriendsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    private function a_user_can_send_a_friend_request()
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
            'id' => $id
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
}
