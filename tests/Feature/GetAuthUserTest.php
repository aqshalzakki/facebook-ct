<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Post;

class GetAuthUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_be_fetched()
    {
        $this->withoutExceptionHandling();
        
        $user = factory(User::class)->create(['name' => 'Aqshal Zakki']);
        $this->actingAs($user, 'api');

        $response = $this->get('api/auth-user');
    
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type'    => 'users',
                    'user_id' => $user->id,
                    'attributes' => [
                        'name' => $user->name
                    ]
                ],
                'links' => [
                    'self' => url("/users/$user->id")
                ]
            ]);
    }
}
