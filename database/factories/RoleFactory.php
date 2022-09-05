<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Role::class, function (Faker $faker) {
    return [
        'name' => $faker->realText('10'),
        'title' => $faker->realText('10'),
        'permissions' => '{"*":true}',
        'created_at' => now()
    ];
});
