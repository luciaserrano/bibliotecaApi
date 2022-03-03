<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Libro;
use Faker\Generator as Faker;

$factory->define(Libro::class, function (Faker $faker) {
    return [
        'titulo' => $faker->sentence(2),
        'descripcion' => $faker->sentence,
    ];
});
