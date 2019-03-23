<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        //hide attributes
        $user->updated_at;

        //get all user's jobs
        $jobs = DB::select("select * from job where employer_id=".$user->id.
            " or jobseeker_id = ".$user->id);

        //user as employer job posts arrays
        $job_posts_open=array(); $job_posts_in_progress=array(); $job_posts_past=array();

        //user as jobseeker jobs arrays
        $jobs_apply=array(); $jobs_in_progress=array(); $jobs_past=array();

        $jobs_apply = DB::select("select * from job where id in (select ja.job_id from job_application as ja, job as j where ja.job_id = j.id and j.status='open' and ja.jobseeker_id=" . $user->id . ");");

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
                if($job->status == "in-progress"){
                    $jobs_in_progress[] = $job;
                }else if($job->status == "incomplete" 
                    || $job->status == "complete" ){
                    $jobs_past[] = $job;
                }
            }
        }

        $user->job_posts_open= $job_posts_open;
        $user->job_posts_in_progress= $job_posts_in_progress;
        $user->job_posts_past= $job_posts_past;

        $user->jobs_apply= $jobs_apply;
        $user->jobs_in_progress= $jobs_in_progress;
        $user->jobs_past= $jobs_past;

        return view("home", compact("user"));
    }
}
