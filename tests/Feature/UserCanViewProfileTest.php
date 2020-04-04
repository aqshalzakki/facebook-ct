<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCanViewProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * A User can see user profile test
     *
     * @author Aqshal Zakki
     */
    public function a_user_can_see_user_profile()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $posts = factory(Post::class)->create();

        $response = $this->get("/api/users/$user->id");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'user_id' => $user->id,
                    'attributes' => [
                        'name' => $user->name
                    ],
                ],
                'links' => [
                    'self' => url("/users/$user->id")
                ]
            ]);
    }
}
