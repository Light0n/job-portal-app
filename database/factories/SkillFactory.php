<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(App\Skill::class, function (Faker $faker) {
    //get all category_id
    // $category_ids = [];

    // $results = DB::select('select category_id from category');
    // $arr_length = sizeof($results);

    // for ($i=0; $i < $arr_length; $i++) { 
    //     $category_ids[$i] = $results[$i]->category_id;
    // }

    $category_ids = getValuesOfColumnByQuery('id', 'select id from category');

    return [
        //create array of 3 words, join together with " " and uppercase 
        //the first letter of each word 
        'skill_name' => ucwords(implode(" ", $faker->words(3))),
        //a sentence with random number of words (max = 10) 
        'description' => $faker->sentence(10, true),
        'category_id' => $category_ids[mt_rand(0, sizeof($category_ids) - 1)]
    ];
});

