<?php

namespace Tests\Feature;

use App\User;
use App\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostToTimelineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_post_a_text_post()
    {
        $this->withoutExceptionHandling();
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api');
    
        $response = $this->post('/api/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'body' => 'This is My Post!'
                ]
            ]
        ]);

        $post = Post::first();

        $response->assertStatus(201);
    }
}
