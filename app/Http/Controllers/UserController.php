<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show($user_id){
        // $result = [];
        // //get user
        $user = DB::select('select * from user where id = ?', [$user_id]);

        // if($user){
        // $result['user'] = $user[0];

        // //get job post by user
        // $jobs = DB::select('select * from job where employer_id = ?', 
        //     [$user_id]);
            
        // $result['jobs'] = $jobs;

        // //get job application from job that does not have picked jobseeker
        // $job_applications = DB::select("select * from job_application as ja, job as j where ja.job_id=j.id and j.status='open' and ja.jobseeker_id = ?", 
        // [$user_id]);

        // $result['ongoing_job_applications'] = $job_applications;

        // //get working on jobs
        // $job_applications = DB::select("select * from job_application as ja, job as j where ja.job_id=j.id and j.jobseeker_id = ja.jobseeker_id and j.jobseeker_status='working' and j.jobseeker_id = ?", [$user_id]);
        
        // $result['working_jobs'] = $job_applications;


        // //get past jobs
        // $job_applications = DB::select("select * from job_application as ja, job as j where ja.job_id=j.id and j.jobseeker_id = ja.jobseeker_id and (j.status='complete' or j.status='incomplete') and j.jobseeker_id = ?", [$user_id]);
        
        // $result['past_jobs'] = $job_applications;
        
        // }
        // return dd($result);


        if($user)
            $user = $user[0];


        //hide attributes
        // $user->updated_at;

        //get all user's jobs
        $jobs = DB::select("select * from job where employer_id=".$user->id.
            " or jobseeker_id = ".$user->id);

        

        //user as employer job posts arrays
        $job_posts_open=array(); $job_posts_in_progress=array(); $job_posts_past=array();

        //user as jobseeker jobs arrays
        $jobs_apply=array(); $jobs_in_progress=array(); $jobs_past=array();

        foreach ($jobs as $job) {
            //hide attributes

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
                if($job->status == "open"){
                    $jobs_apply[] = $job;
                }else if($job->status == "in-progress"){
                    $jobs_in_progress[] = $job;
                }else if($job->status == "incomplete" 
                    || $job->status == "complete" ){
                    $jobs_past[] = $job;
                }
            }
        }

        $jobs_apply = DB::select("select * from job where id in (select ja.job_id from job_application as ja, job as j where ja.job_id = j.id and j.status='open' and ja.jobseeker_id=" . $user->id . ");");

        $user->job_posts_open= $job_posts_open;
        $user->job_posts_in_progress= $job_posts_in_progress;
        $user->job_posts_past= $job_posts_past;

        $user->jobs_apply= $jobs_apply;
        $user->jobs_in_progress= $jobs_in_progress;
        $user->jobs_past= $jobs_past;

        return view("user", compact("user"));
    }
}
