<?php

use Faker\Generator as Faker;

$factory->define(App\Job::class, function (Faker $faker) {
    //get all user_id
    $user_ids = getValuesOfColumnByQuery('id', 'select id from user');

    return [
        'employer_id' => mt_rand(0, sizeof($user_ids) - 1),
        'title' => ucwords(implode(" ", $faker->words(mt_rand(5,10)))),
        'description' => $faker->paragraph(),
        'estimated_budget'=> $faker->numberBetween(10, 10000),
        'city' => $faker->city,
        'province' => $faker->state,
        'country' => $faker->country,
        'status'=>'open',
        'employer_status'=>'posted'
    ];
});

// $table->integer('jobseeker_id')->unsigned()->nullable();
// $table->string('jobseeker_status', 50)->nullable();
