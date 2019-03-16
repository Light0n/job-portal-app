<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        //create array of 3 words, join together with " " and uppercase 
        //the first letter of each word 
        'category_name' => ucwords(implode(" ", $faker->words(mt_rand(1,3)))),
        //a sentence with random number of words (max = 10) 
        'description' => $faker->sentence(10, true)
    ];
});
