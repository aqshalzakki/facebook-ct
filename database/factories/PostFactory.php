<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'body' => $faker->sentence(),
        'image' => 'https://i0.wp.com/www.saveseva.com/wp-content/uploads/2015/06/Landscape.jpg?fit=640%2C324'
    ];
});
