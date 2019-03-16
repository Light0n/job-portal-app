<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function index(){
        //show job that have status 'open'
        $jobs = DB::select("select * from job where status='open'");
        foreach ($jobs as &$job) {//pass $job by reference
            //hide attributes
            unset($job->updated_at);
            unset($job->jobseeker_id);
            unset($job->jobseeker_status);
            unset($job->employer_status);
            unset($job->jobseeker_review_id);
            unset($job->employer_review_id);
            //get array of required skills
            $job->required_skills = DB::select("select s.id, s.skill_name
                from job as j join job_required_skill as jrs 
                    on j.id = jrs.job_id 
                    join skill as s 
                    on jrs.skill_id = s.id 
                    where j.id=".$job->id);
            //get employer info
            $job->employer_info = DB::select('select 
                total_employer_reviews, employeer_avg_rate
                from user where id = '.$job->employer_id);
        }
        unset($job);//destroy reference variable
        return view("jobs", compact("jobs"));
        // return dd(DB::select("select * from job where status='open'"));
    }
}
