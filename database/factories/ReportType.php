<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\ReportType;
use Faker\Generator as Faker;

$factory->define(ReportType::class, function (Faker $faker) {
    return [
        'title' => $faker->realText('10'),
        'description' => $faker->realText('100'),
        'en_title' => null,
        'en_description' => null,
        'importance_level' => rand(1,5),
        'created_at' => now()
    ];
});
