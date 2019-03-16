<?php

use Faker\Generator as Faker;

$factory->define(App\JobRequiredSkill::class, function (Faker $faker) {
    //get all skill_id
    $skill_ids = getValuesOfColumnByQuery('id', 'select id from skill');

    //get all job_id
    $job_ids = getValuesOfColumnByQuery('id', 'select id from job');

    //get a unique set of job_id and skill_id
    while(TRUE){
        $job_id = $job_ids[mt_rand(0, sizeof($job_ids) - 1)];
        $skill_id = $skill_ids[mt_rand(0, sizeof($skill_ids) - 1)];

        if(sizeof(DB::select('select * from job_required_skill where job_id='.$job_id.' and skill_id='.$skill_id)) == 0){
            // error_log('job_id='.$job_id.' skill_id='.$skill_id);
            break;
        }
    }

    return [
        'job_id' => $job_id, 
        'skill_id' => $skill_id 
    ];
});
