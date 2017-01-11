<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(App\Book::class, function (Faker\Generator $faker) {
    return [
        'author_id' => factory(App\Author::class)->create()->id,
        'title' => $faker->sentence,
        'description' => $faker->text,
    ];
});

$factory->define(App\Author::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'gender' => rand(1, 6) % 2 == 0 ? 'male' : 'female',
        'biography' => $faker->text(),
    ];
});


