<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Profile;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\User::class)->create()->id,
        'email' => $faker->unique()->safeEmail,
        'sex' => 0,
        'activated' => 1,
        'invitation_link' => Str::random(32),
        'invited_by_user_id' => null,
        'lat' => $faker->randomFloat(6),
        'lang' => $faker->randomFloat(6),
        'city_id' => null,
        /*'birthday_year' => null,
        'address' => '',
        'company' => '',
        'en_company' => '',
        'abount' => '',
        'en_about' => '',
        'specialist' => '',
        */
    ];
});
