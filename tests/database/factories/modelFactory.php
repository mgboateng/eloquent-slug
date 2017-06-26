<?php

use MGBoateng\EloquentSlugs\Test\Post;

$factory->define(Post::class, function (Faker\Generator $faker) {
    $title = $faker->sentence;
    return [        
        'title' => $title,
        'body' => $faker->paragraph,
        'slug' => str_slug($title)        
    ];
});