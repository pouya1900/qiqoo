<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\FileType::class, function (Faker $faker) {
    return [
        'name' => $faker->realText(10),
        'title' => $faker->realText(20),
        'type' => $faker->realText(20),
        'description' => $faker->realText(50),
        'valid_ext' => $faker->text(5),
        'created_at' => now()
    ];
});
