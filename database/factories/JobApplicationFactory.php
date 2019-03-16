<?php

use Faker\Generator as Faker;

$factory->define(App\JobApplication::class, function (Faker $faker) {
    //get all user_id
    $user_ids = getValuesOfColumnByQuery('id', 'select id from user');

    //get a unique set of job_id and user_id
    while(TRUE){
        $user_id = $user_ids[mt_rand(0, sizeof($user_ids) - 1)];

        //get all job_id not from $user_id
        $job_ids = getValuesOfColumnByQuery('id', 'select id from job where employer_id<>'.$user_id);

        //get random job_id
        $job_id = $job_ids[mt_rand(0, sizeof($job_ids) - 1)];

        if(sizeof(DB::select('select * from job_application where job_id='.$job_id.' and jobseeker_id='.$user_id)) == 0){
            // error_log('job_id='.$job_id.' user_id='.$user_id);
            break;
        }
    }

    return [
        'job_id' => $job_id, 
        'jobseeker_id' => $user_id,
        'bid_value' => mt_rand(10,10000),
        'bid_completion_day'=> mt_rand(1,30)
    ];
});
