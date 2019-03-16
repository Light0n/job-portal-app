<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(App\UserSkill::class, function (Faker $faker) {
    //get all skill_id
    $skill_ids = getValuesOfColumnByQuery('id', 'select id from skill');

    //get all user_id
    $user_ids = getValuesOfColumnByQuery('id', 'select id from user');

    //get a unique set of user_id and skill_id
    while(TRUE){
        $user_id = $user_ids[mt_rand(0, sizeof($user_ids) - 1)];
        $skill_id = $skill_ids[mt_rand(0, sizeof($skill_ids) - 1)];

        if(sizeof(DB::select('select * from user_skill where user_id='.$user_id.' and skill_id='.$skill_id)) == 0){
            // error_log('user_id='.$user_id.' skill_id='.$skill_id);
            break;
        }
    }

    return [
        'user_id' => $user_id, 
        'skill_id' => $skill_id 
    ];
});

