<?php

namespace Tests\Feature;

use App\User;
use App\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->assertCount(1, Post::all());
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals('This is My Post!', $post->body);

        $response->assertStatus(201)
                 ->assertJson([
                    'data' => [
                        'type'    => 'posts',
                        'post_id' => $post->id,
                        'attributes' => [
                            'posted_by' => [
                                'data' => [
                                    'attributes' => [
                                        'name' => $user->name
                                    ]
                                ]
                            ],
                            'body' => 'This is My Post!'
                        ]
                    ],
                    'links' => [
                        'self' => route('posts.index')
                    ]
                 ]);
    }
}
