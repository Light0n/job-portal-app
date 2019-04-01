<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\JobApplication;
use App\Job;

class JobApplicationController extends Controller
{
    // create job application
    public function create(Request $request){
        $job_id = request()->job_id;
        $jobseeker_id = request()->jobseeker_id;
        $job_title = request()->job_title;

        return view("application.create", compact('job_id', 'jobseeker_id', 'job_title'));
    }

    public function store(Request $request){
        //validate
        $job_application = $this->validate(request(), [
            'job_id' => 'required',
            'jobseeker_id' => 'required',
            'bid_value' => 'required|numeric',
            'bid_completion_day' => 'required|numeric'
          ]);
          
        JobApplication::create($job_application);

        return redirect('/jobs/'.request()->job_id)->with('message', ['success', 'Application for "'.request()->job_title.'" has been created']);
    }

    //delete a job application
    public function destroy($job_id)
    {

        if(Auth::user()->id == request()->jobseeker_id){
            DB::select('delete from job_application where job_id = '. $job_id . ' and jobseeker_id = '. Auth::user()->id);
            return back()->with('message', ['success', 'Job Application for '. request()->job_title .' is deleted']);
        }else{
            return back()->with('message', ['danger', 'Job Application for '. request()->job_title .' cannot be deleted']);
        }
    }

    //edit a job application
    public function edit($job_id){
        $job_application = DB::select('select * from job_application where job_id = '. $job_id . ' and jobseeker_id = '. Auth::user()->id);

        //get job
        $job = Job::find($job_id);//get job
        $job_title = $job->title;

        if(sizeof($job_application)){
            $job_application = $job_application[0];

            return view('application.edit', compact('job_application', 'job_title'));
        }else{
            return back()->with('message', ['danger', 'Job Application for "'.$job_title.'" cannot be found']);
        }
    }

    public function update(Request $request, $job_id){

        $job_application = DB::select('select * from job_application where job_id = '. $job_id . ' and jobseeker_id = '. Auth::user()->id);

        //get job
        $job = Job::find($job_id);//get job
        $job_title = $job->title;

        if(sizeof($job_application)){

            DB::update('update job_application set bid_value = '. request()->bid_value .', bid_completion_day = '. request()->bid_completion_day .', updated_at="'. date("Y-m-d h:i:s").'" where job_id =' . $job_id . ' and jobseeker_id = '. Auth::user()->id);

            return back()->with('message', ['success', 'Job Application for "'.$job_title.'" is updated']);
        }else{
            return back()->with('message', ['danger', 'Job Application for "'.$job_title.'" cannot be found']);
        }
    }
}
