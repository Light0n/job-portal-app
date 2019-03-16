<?php

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

$factory->define(App\User::class, function (Faker $faker) {
    
    return [
        'first_name' => $faker->name,
        'last_name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        // 'password' => password_hash("jobportal", PASSWORD_BCRYPT, $options),
        'password' => bcrypt("jobportal"),
        'remember_token' => str_random(10),
        'phone' => $faker->tollFreePhoneNumber,
        'city' => $faker->city,
        'province' => $faker->state,
        'country' => $faker->country,
    ];
});

// $table->unsignedSmallInteger('total_employer_reviews')->nullable();
// $table->decimal('employeer_avg_rate', 3, 2)->nullable();
// $table->unsignedSmallInteger('total_jobseeker_reviews')->nullable();
// $table->decimal('jobseeker_avg_rate', 3, 2)->nullable();