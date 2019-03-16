<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show($user_id){
        $result = [];
        //get user
        $user = DB::select('select * from user where id = ?', [$user_id]);

        if($user){
        $result['user'] = $user[0];

        //get job post by user
        $jobs = DB::select('select * from job where employer_id = ?', 
            [$user_id]);
            
        $result['jobs'] = $jobs;

        //get job application from job that does not have picked jobseeker
        $job_applications = DB::select("select * from job_application as ja, job as j where ja.job_id=j.id and j.status='open' and ja.jobseeker_id = ?", 
        [$user_id]);

        $result['ongoing_job_applications'] = $job_applications;

        //get working on jobs
        $job_applications = DB::select("select * from job_application as ja, job as j where ja.job_id=j.id and j.jobseeker_id = ja.jobseeker_id and j.jobseeker_status='working' and j.jobseeker_id = ?", [$user_id]);
        
        $result['working_jobs'] = $job_applications;


        //get past jobs
        $job_applications = DB::select("select * from job_application as ja, job as j where ja.job_id=j.id and j.jobseeker_id = ja.jobseeker_id and (j.status='complete' or j.status='incomplete') and j.jobseeker_id = ?", [$user_id]);
        
        $result['past_jobs'] = $job_applications;
        
        }
        return dd($result);
    }
}
