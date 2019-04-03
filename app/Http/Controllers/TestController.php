<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    //show a user
    public function showUser($user_id){
        $user = DB::select('select * from user where id = '. $user_id);

        if($user)
            $user = $user[0];
        else
            return;

        //hide attributes
        unset($user->password);
        unset($user->remember_token);

        //get array of user skills
        $user->user_skills = DB::select("select s.id, s.skill_name
        from skill as s join user_skill as us 
        on us.skill_id = s.id 
        where us.user_id=".$user->id);

        //user as employer job posts arrays
        $job_posts_open=array(); $job_posts_in_progress=array(); $job_posts_past=array();

        //user as jobseeker jobs arrays
        $jobs_apply=array(); $jobs_in_progress=array(); $jobs_past=array();

        //get all user's jobs
        $jobs = DB::select("select * from job where status<>'delete' and (employer_id=".$user->id.
            " or jobseeker_id = ".$user->id.")");

        $jobs_apply = DB::select("select * from job where id in (select ja.job_id from job_application as ja, job as j where ja.job_id = j.id and j.status='open' and ja.jobseeker_id=" . $user->id . ");");

        $jobs = array_merge($jobs, $jobs_apply);

        foreach ($jobs as $job) {
            //hide attributes

            //get the job applications 
            $job_applications = DB::select('select * from job_application
            where job_id ='.$job->id);
            
            $count = 0;
            $total_bid_value = 0;

            //get employer info
            $employer_info = DB::select('select * from 
            user where id = '.$job->employer_id)[0];

            //hide attributes
            unset($employer_info->password);
            unset($employer_info->remember_token);
            unset($employer_info->employer_avg_rate);
            unset($employer_info->total_employer_reviews);

            $job->employer_info = $employer_info;

            foreach ($job_applications as &$application) {
                //get jobseeker info
                $jobseeker_info = DB::select('select * from 
                    user where id = '.$application->jobseeker_id)[0];
    
                //hide attributes
                unset($jobseeker_info->password);
                unset($jobseeker_info->remember_token);
                unset($jobseeker_info->employer_avg_rate);
                unset($jobseeker_info->total_employer_reviews);
    
                $application->jobseeker_info = $jobseeker_info;
                
                //get array of jobseeker skills
                $application->jobseeker_info->user_skills = DB::select("select s.id, s.skill_name
                    from skill as s join user_skill as us 
                    on us.skill_id = s.id 
                    where us.user_id=".$jobseeker_info->id);
    
                //prepare count and total
                $count++;
                $total_bid_value += $application->bid_value;
            }

            //number of bids
            $job->number_of_application =  $count;

            //AVG bid
            $job->avg_bid = $count? $total_bid_value/$count : $total_bid_value;

            //jobseeker info
            if($job->jobseeker_id){
                //get jobseeker info
                $job->jobseeker_info = DB::select('select * from 
                    user where id = '.$job->jobseeker_id)[0];

                //hide attributes
                unset($job->jobseeker_info->password);
                unset($job->jobseeker_info->remember_token);
                unset($job->jobseeker_info->employer_avg_rate);
                unset($job->jobseeker_info->total_employer_reviews);
            }

            //job_applications
            $job->job_applications = $job_applications;

            if($job->employer_id == $user->id){
                if($job->status == "open"){
                    $job_posts_open[] = $job;
                }else if($job->status == "in-progress"){
                    $job_posts_in_progress[] = $job;
                }else if($job->status == "incomplete" 
                    || $job->status == "complete" ){
                    $job_posts_past[] = $job;
                }
            }else if($job->jobseeker_id == $user->id){
                if($job->status == "in-progress"){
                    $jobs_in_progress[] = $job;
                }else if($job->status == "incomplete" 
                    || $job->status == "complete" ){
                    $jobs_past[] = $job;
                }else{
                    $jobs_apply[] = $job;
                }
            }
        }

        $user->job_posts_open= $job_posts_open;
        $user->job_posts_in_progress= $job_posts_in_progress;
        $user->job_posts_past= $job_posts_past;

        $user->jobs_apply= $jobs_apply;
        $user->jobs_in_progress= $jobs_in_progress;
        $user->jobs_past= $jobs_past;

        dd($user);

        return view("user", compact("user"));
    }

    //show all jobs
    public function showJobs(){
        //show job that have status 'open'
        $jobs = DB::select("select * from job");
        foreach ($jobs as &$job) {//pass $job by reference
            //hide attributes
            unset($job->updated_at);
            unset($job->jobseeker_id);
            unset($job->jobseeker_status);
            unset($job->employer_status);
            unset($job->jobseeker_review_id);
            unset($job->employer_review_id);
            //number of bids
            $job->number_of_application =  DB::select('select count(job_id) as count 
                from job_application where job_id = '.$job->id)[0]->count;
            //get array of required skills
            $job->required_skills = DB::select("select s.id, s.skill_name
                from job as j join job_required_skill as jrs 
                    on j.id = jrs.job_id 
                    join skill as s 
                    on jrs.skill_id = s.id 
                    where j.id=".$job->id);
            //get employer info
            $job->employer_info = DB::select('select 
                total_employer_reviews, employer_avg_rate
                from user where id = '.$job->employer_id);
        }
        unset($job);//destroy reference variable

        dd($jobs);
    }

    //show a job
    public function showJob($job_id){
        //get the job
        $job = DB::select("select * from job where id=".$job_id);

        //if job cannot access
        if(sizeof($job)){
            $job = $job[0];
        }else{//redirect home
            return redirect("/home");
        }

        //get the job applications 
        $job_applications = DB::select('select * from job_application
            where job_id ='.$job_id);
        
        $count = 0;
        $total_bid_value = 0;
        $pickedApplication = 0;

        if($job->status == "in-progress" || $job->status == "complete" ||
                $job->status == "incomplete")
            $pickedApplication = 1;
        
        foreach ($job_applications as &$application) {
            //get jobseeker info
            $jobseeker_info = DB::select('select * from 
                user where id = '.$application->jobseeker_id)[0];

            //hide attributes
            unset($jobseeker_info->password);
            unset($jobseeker_info->remember_token);
            unset($jobseeker_info->employer_avg_rate);
            unset($jobseeker_info->total_employer_reviews);

            $application->jobseeker_info = $jobseeker_info;
            
            //get array of jobseeker skills
            $application->jobseeker_info->user_skills = DB::select("select s.id, s.skill_name
                from skill as s join user_skill as us 
                on us.skill_id = s.id 
                where us.user_id=".$jobseeker_info->id);

            //get picked application position
            if($pickedApplication && $jobseeker_info->id == $job->jobseeker_id)
                $pickedApplication = $count;

            //prepare count and total
            $count++;
            $total_bid_value += $application->bid_value;
        }

        //number of bids
        $job->number_of_application =  $count;

        //AVG bid
        $job->avg_bid = $count? $total_bid_value/$count : $total_bid_value;

        //get array of required skills
        $job->required_skills = DB::select("select s.id, s.skill_name
            from job as j join job_required_skill as jrs 
                on j.id = jrs.job_id 
                join skill as s 
                on jrs.skill_id = s.id 
                where j.id=".$job->id);
        //get employer info
        $job->employer_info = DB::select('select 
            email, total_employer_reviews, employer_avg_rate
            from user where id = '.$job->employer_id);

        // set picked application to top
        if($pickedApplication){
            $temp = $job_applications[0];
            $job_applications[0] = $job_applications[$pickedApplication];
            $job_applications[$pickedApplication] = $temp;
        }

        $job->job_applications = $job_applications;

        dd($job);
    }
}
