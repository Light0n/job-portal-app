<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        define('NUM_CATEGORY', 5); 
        define('NUM_SKILL', 50); 
        define('NUM_USER', 10); 
        define('NUM_USER_SKILL', 100); 
        define('NUM_JOB', 20); 
        define('NUM_JOB_REQUIRED_SKILL', 100);
        define('NUM_JOB_APPLICATION', 100);

        // $this->call(UsersTableSeeder::class);

        (new Faker\Generator)->seed(789);//make sure same data is created

        //Category
        \App\Category::truncate();
        factory(App\Category::class, NUM_CATEGORY)->create();

        //Skill
        \App\Skill::truncate();
        factory(App\Skill::class, NUM_SKILL)->create();

        //User
        \App\User::truncate();
        factory(App\User::class, NUM_USER)->create();

        //User_Skill
        \App\UserSkill::truncate();
        for ($i=0; $i < NUM_USER_SKILL ; $i++) { //create 100 user_skill
            factory(App\UserSkill::class)->create();
        }

        //Job
        \App\Job::truncate();
        factory(App\Job::class, NUM_JOB)->create();
        
        //JobRequiredSkill
        \App\JobRequiredSkill::truncate();
        for ($i=0; $i < NUM_JOB_REQUIRED_SKILL ; $i++) { //create 100 job_required_skill
            factory(App\JobRequiredSkill::class)->create();
        }

        //JobApplication
        \App\JobApplication::truncate();
        for ($i=0; $i < NUM_JOB_APPLICATION ; $i++) { //create 100 job_application
            factory(App\JobApplication::class)->create();
        }
//about 50% of jobs have jobseeker chosen
            //get 50% random jobs
            $rand_jobs = getValuesOfColumnByQuery('id', 
            'select id from job order by rand() limit '.NUM_JOB/2);

            //assign jobseeker_id from list of job_application
        for($i = 0; $i<NUM_JOB/2; $i++){
            $job_id = $rand_jobs[$i];
            //get jobseeker_id
            $jobseeker_ids = getValuesOfColumnByQuery('jobseeker_id', 
                'select jobseeker_id from job_application where job_id='.$job_id);
            if(sizeof($jobseeker_ids) > 0){
                $jobseeker_id = $jobseeker_ids[mt_rand(0, sizeof($jobseeker_ids) - 1)];

                //update job table
                DB::select("update job set jobseeker_status='working', employer_status='picked', status='in-progress', jobseeker_id=".$jobseeker_id.
                    " where id=".$job_id);
            }
        }

        //about 50% of in-progress job reach end states
        //jobseeker: cancel
        //employer: complete, incomplete
        $in_progress_jobs = getValuesOfColumnByQuery('id', 
            "select id from job where status='in-progress' order by rand()");
        
            // DB::select( DB::raw("SELECT * FROM some_table WHERE some_col = :somevariable"), array(
            //     'somevariable' => $someVariable,
            //   ));

        for($i = 0; $i<sizeof($in_progress_jobs)/2; $i++){
            $job_id = $in_progress_jobs[$i];

            //get $job info
            $job = DB::select("select employer_id, jobseeker_id from job where id=".$job_id);

            $review_id = 0;

            switch(mt_rand(0,2)){
                case 0://jobseeker cancel the job
                    //update job table
                    // DB::select("update job set jobseeker_status='canceled', employer_status='picked', status='incomplete' where job_id=".$job_id);
                    if(mt_rand(0,1)){
                        //addReview("insert into employer_review (job_id, employer_id, employer_rate, review_content, by_jobseeker_id) values(:job_id, :to_id, :review_rate, :review_content, :from_id)", $job_id, $job[0]->employer_id, mt_rand(1,5), NULL, $job[0]->jobseeker_id);
                        $rate = mt_rand(1,5);
                        $review_id = addReview('employer_review', $rate);
                        //update job table
                        DB::select("update job set jobseeker_status='canceled', employer_status='picked', status='incomplete', employer_review_id=".$review_id." where id=".$job_id);

                        updateUserRate($job[0]->employer_id, $rate, "employer");
                    }
                    if(mt_rand(0,1)){
                        //addReview("insert into jobseeker_review (job_id, jobseeker_id, jobseeker_rate, review_content, by_employer_id) values(:job_id, :to_id, :review_rate, :review_content, :from_id)", $job_id, $job[0]->jobseeker_id, mt_rand(1,5), NULL, $job[0]->employer_id);

                        $rate = mt_rand(1,5);
                        $review_id = addReview('jobseeker_review', $rate);
                        DB::select("update job set jobseeker_status='canceled', employer_status='picked', status='incomplete', jobseeker_review_id=".$review_id." where id=".$job_id);

                        updateUserRate($job[0]->jobseeker_id, $rate, "jobseeker");
                    }
                    
                    break;
                case 1://employer cancel the job
                    //update job table
                    // DB::select("update job set jobseeker_status='working', employer_status='canceled', status='incomplete' where job_id=".$job_id);
                    if(mt_rand(0,1)){
                        //addReview("insert into employer_review (job_id, employer_id, employer_rate, review_content, by_jobseeker_id) values(:job_id, :to_id, :review_rate, :review_content, :from_id)", $job_id, $job[0]->employer_id, mt_rand(1,5), NULL, $job[0]->jobseeker_id);
                        $rate = mt_rand(1,5);
                        $review_id = addReview('employer_review', $rate);
                        DB::select("update job set jobseeker_status='working', employer_status='canceled', status='incomplete', employer_review_id=".$review_id." where id=".$job_id);

                        updateUserRate($job[0]->employer_id, $rate, "employer");
                    }
                    if(mt_rand(0,1)){
                        //addReview("insert into jobseeker_review (job_id, jobseeker_id, jobseeker_rate, review_content, by_employer_id) values(:job_id, :to_id, :review_rate, :review_content, :from_id)", $job_id, $job[0]->jobseeker_id, mt_rand(1,5), NULL, $job[0]->employer_id);
                        $rate = mt_rand(1,5);
                        $review_id = addReview('jobseeker_review', $rate);
                        DB::select("update job set jobseeker_status='working', employer_status='canceled', status='incomplete', jobseeker_review_id=".$review_id." where id=".$job_id);

                        updateUserRate($job[0]->jobseeker_id, $rate, "jobseeker");
                    }
                    break;
                case 2://employer accept the job is completed
                    //update job table
                    DB::select("update job set jobseeker_status='done', employer_status='accepted', status='complete' where id=".$job_id);
                    if(mt_rand(0,1)){
                        //addReview("insert into employer_review (job_id, employer_id, employer_rate, review_content, by_jobseeker_id) values(:job_id, :to_id, :review_rate, :review_content, :from_id)", $job_id, $job[0]->employer_id, mt_rand(1,5), NULL, $job[0]->jobseeker_id);

                        $rate = mt_rand(1,5);
                        $review_id = addReview('employer_review', $rate);
                        DB::select("update job set jobseeker_status='done', employer_status='accepted', status='complete', employer_review_id=".$review_id." where id=".$job_id);

                        updateUserRate($job[0]->employer_id, $rate, "employer");
                    }
                    if(mt_rand(0,1)){
                        //addReview("insert into jobseeker_review (job_id, jobseeker_id, jobseeker_rate, review_content, by_employer_id) values(:job_id, :to_id, :review_rate, :review_content, :from_id)", $job_id, $job[0]->jobseeker_id, mt_rand(1,5), NULL, $job[0]->employer_id);

                        $rate = mt_rand(1,5);
                        $review_id = addReview('jobseeker_review', $rate);
                        DB::select("update job set jobseeker_status='done', employer_status='accepted', status='complete', jobseeker_review_id=".$review_id." where id=".$job_id);

                        updateUserRate($job[0]->jobseeker_id, $rate, "jobseeker");
                    }
                    break;
            }
        }
        

    }//cmd: php artisan db:seed
}
// recreate entire database with seed data: php artisan migrate:refresh --seed

// helper functions use in factories
function getValuesOfColumnByQuery($column, $query){
    $setOfValues = [];

    $results = DB::select($query);
    $arr_length = sizeof($results);

    for ($i=0; $i < $arr_length; $i++) { 
        $setOfValues[$i] = $results[$i]->$column;
    }

    return $setOfValues;
}

// function addReview($query, 
//     $job_id, $to_id, $review_rate, $review_content=NULL, $from_id){
//     DB::select($query, array(
//         'job_id' => $job_id,
//         'to_id' => $to_id,
//         'review_rate' => $review_rate,
//         'review_content' => $review_content,
//         'from_id' => $from_id
//     ));
// }

//create a review and return review_id
function addReview($table, $rate, $review_content=NULL){

    $id = DB::table($table)->insertGetId(
        [ 'rate' => $rate,
          'review_content' => $review_content
        ]
    );

    return $id;
}

function updateUserRate($user_id, $newRate, $user_type){
    $user = DB::select("select * from user where id=".$user_id);

    switch($user_type){
        case "employer":
            $numberOfReview = $user[0]->total_employer_reviews;
            $avegareRate = $user[0]->employeer_avg_rate;

            DB::select("update user set total_employer_reviews=:numberOfReview, employeer_avg_rate=:avegareRate where id=:user_id", array(
                'user_id' => $user_id,
                'numberOfReview' => $numberOfReview + 1,
                'avegareRate' => ($numberOfReview * $avegareRate + $newRate)/($numberOfReview + 1)
            ));
            break;
        case "jobseeker":
            $numberOfReview = $user[0]->total_jobseeker_reviews;
            $avegareRate = $user[0]->jobseeker_avg_rate;

            DB::select("update user set total_jobseeker_reviews=:numberOfReview, jobseeker_avg_rate=:avegareRate where id=:user_id", array(
                'user_id' => $user_id,
                'numberOfReview' => $numberOfReview + 1,
                'avegareRate' => ($numberOfReview * $avegareRate + $newRate)/($numberOfReview + 1)
            ));
            break;
    }
}

//testing sql
//select job_id, employer_id, jobseeker_id, employer_review_id, jobseeker_review_id, status, jobseeker_status, employer_status from job;