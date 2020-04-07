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
}
