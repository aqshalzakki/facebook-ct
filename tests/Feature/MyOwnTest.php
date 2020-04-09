<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MyOwnTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Foo test
     * @test
     * @author Aqshal Zakki
     */
    public function it_should_return_a_random_token()
    {
        $this->withoutExceptionHandling();

        $this->get('token')
            ->assertExactJson([
                'token' => 'random token'
            ]);
    }

    /** @test */
    public function it_should_return_user_name_with_uppercase()
    {
    	$this->withoutExceptionHandling();
    	$user = factory(User::class)->create();

    	$response = $this->get('username');

    	$username = $response->decodeResponseJson()['name'];

    	$response->assertExactJson([
    		'name' => $user->name
    	]);

    	$this->assertEquals($user->name, $username);
    }

    /** @test */
    public function it_should_return_user_role()
    {
    	$this->withoutExceptionHandling();
    	$user = factory(User::class)->create(['role_id' => 2]);

    	$response = $this->get('role-id');

    	$roleId = $response->decodeResponseJson()['role_id'];

    	$response->assertExactJson([
    		'role_id' => $user->role_id
    	]);

        $this->assertEquals($user->role_id, $roleId);
    }
}
