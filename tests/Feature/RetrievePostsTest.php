<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\User;
use App\Post;

class RetrievePostsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     *  @test
     */
    public function a_user_can_retrieve_posts()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $posts = factory(Post::class, 2)->create(['user_id' => $user->id]);

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $posts->last()->id,
                            'attributes' => [
                                'body' => $posts->last()->body
                            ]
                        ]
                    ],
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $posts->first()->id,
                            'attributes' => [
                                'body' => $posts->first()->body
                            ]
                        ]
                    ]
                ],
                'links' => [
                    'self' => route('posts.index')
                ]
            ]);
    }

    /** @test */
    public function a_user_can_only_retrieve_their_posts()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $posts = factory(Post::class)->create();

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [],
                'links' => [
                    'self' => route('posts.index')
                ]
            ]);
    }
}
