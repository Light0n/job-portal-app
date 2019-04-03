<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Job;
use App\User;

class ReviewController extends Controller
{
    public function create($job_id){
        
        $job = Job::find($job_id);//get job
 
        if($job == null)
            return redirect("/");
        
        

        if($job->employer_id == Auth::user()->id){
            //review to jobseeker
            $review_type = "jobseeker_review";
            //get reviewed_user_info
            $reviewed_user_info = 
            DB::select('select id, first_name, last_name, email from user where id = '.$job->jobseeker_id)[0];
        }else{
            //review to employer
            $review_type = "employer_review";
            //get reviewed_user_info
            $reviewed_user_info = 
            DB::select('select first_name, last_name, email from user where id = '.$job->employer_id)[0];
        }

        $job->reviewed_user_info = $reviewed_user_info;
        
        if($job->jobseeker_id == Auth::user()->id 
        || $job->employer_id == Auth::user()->id )
            return view("review.create", compact('job', 'review_type'));
        else 
            return redirect("/");
    }

    public function store(Request $request){
        // validate
        $review = $this->validate(request(), [
            'job_id' => 'required|numeric',
            'rate' => 'required|numeric',
            'review_content' => 'nullable'
          ]);

        // DB::insert('insert into ? (rate, review_content) values (?, ?)', [request()->review_type, request()->rate, request()->review_content]);
        // dd(request());

        //create a review
        $id = DB::table(request()->review_type)->insertGetId(array('rate' => request()->rate, 'review_content' => request()->review_content));

        //update job table and user table
        if(request()->review_type == "employer_review"){
            DB::update('update job set employer_review_id = ? where id = ?', [$id, request()->job_id]);

            $this->updateUserRate(request()->employer_id, request()->rate, "employer");

        }
        else{
            DB::update('update job set jobseeker_review_id = ? where id = ?', [$id, request()->job_id]);

            $this->updateUserRate(request()->jobseeker_id, request()->rate, "jobseeker");
        }

        return redirect('/home');
    }

    function updateUserRate($user_id, $newRate, $user_type){
        $user = DB::select("select * from user where id=".$user_id);
    
        switch($user_type){
            case "employer":
                $numberOfReview = $user[0]->total_employer_reviews;
                $avegareRate = $user[0]->employer_avg_rate;
    
                DB::select("update user set total_employer_reviews=:numberOfReview, employer_avg_rate=:avegareRate where id=:user_id", array(
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
}
