<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\App\BlogCategory::class, function (Faker $faker) {
    $admins = \App\User::select()->with('roles')->whereHas('roles', function($admin){
       $admin->whereIn('name', ['superAdmin', 'generalAdmin']);
    })->pluck('id')->toArray();

    return [
        'admin_id' => $admins[array_rand($admins)],
        'title' => $faker->realText(15),
        'en_title' => null,
        'description' => $faker->realText(500,5),
        'en_description' => null,
        'parent_id' => null,
        'created_at' => now()
    ];
});
