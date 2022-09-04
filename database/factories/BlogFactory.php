<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Blog;
use Faker\Generator as Faker;

$factory->define(Blog::class, function (Faker $faker) {

    $admins = \App\User::select()->with('roles')->whereHas('roles', function($admin){
        $admin->whereIn('name', ['superAdmin', 'generalAdmin']);
    })->pluck('id')->toArray();

    $categories = \App\BlogCategory::all()->pluck('id')->toArray();

    return [
        'admin_id' => $admins[array_rand($admins)],
        'category_id' => $categories[array_rand($categories)],
        'title' => $title = $faker->realText(30),
        'en_title' => null,
        'short_description' => $faker->realText(1000),
        'en_short_description' => null,
        'meta_title' => $title,
        'meta_description' => $faker->realText(300),
        'meta_keywords' => str_replace(' ', ',', $faker->realText(50)),
        'meta_author' => 'تیم نرم افزار کی کو',
        'text' => $faker->realText(2000),
        'en_text' => null,
        'published' => 1,
        'published_at' => now(),
        'created_at' => now(),
    ];
});
