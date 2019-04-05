<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Job;
use App\User;

class JobController extends Controller
{
    public function index(){
        //show job that have status 'open'
        $jobs = DB::select("select * from job where status='open' order by id desc");
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
      
        return view("job.index", compact("jobs"));
    }

    public function show($job_id){
        //get the job
        $job = DB::select("select * from job where status<>'delete' and id=".$job_id);

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

        // dd($job);
        
        return view("job.show", compact("job"));
    }

    public function create(){
        //get all skills and their categories
        $skills = DB::select("select s.id, s.skill_name, s.description, c.category_name from skill as s, category as c where s.category_id = c.id");

        return view("job.create", compact('skills'));
    }

    public function store(Request $request){
        //validate
        $job = $this->validate(request(), [
            'employer_id' => 'required',
            'title' => 'required',
            'description' => 'nullable',
            'estimated_budget' => 'required|numeric',
            'city' => 'nullable',
            'province' => 'nullable',
            'country' => 'nullable'
          ]);


        //set default job state
        $job["status"] = "open";
        $job["employer_status"] = "posted";
    
        // dd($job);

        $newJob = Job::create($job);// create a job

        // set job_required_skills
        if($request->get('skill_ids'))
        foreach ($request->get('skill_ids') as $skill_id) {
            DB::select("insert into job_required_skill (job_id, skill_id) 
                values('".$newJob->id."', '".$skill_id."');");
        }

        return back()->with('message', ['success', 'Job "'.$job["title"]
        .'" has been posted']);
    }

    // edit job post info and required skills
    public function edit($job_id){
        //get all skills and their categories
        $skills = DB::select("select s.id, s.skill_name, s.description, c.category_name from skill as s, category as c where s.category_id = c.id");

        $job = Job::find($job_id);//get job

        //get current job required skills
        $job_required_skills = DB::select("select * from job_required_skill where job_id =".$job_id);

        //if set selected property of all skills array based on current job_required_skills
        foreach ($skills as &$skill) {
            $skill->selected = 0;
            for($i=0; $i < sizeof($job_required_skills); $i++){
                if($skill->id == $job_required_skills[$i]->skill_id){
                    $skill->selected = 1;
                    break;
                }
            }
        }

        // dd($skills);

        return view('job.edit', compact('skills', 'job', 'job_id'));
    }

    public function update(Request $request, $job_id){
        //validate
        $this->validate(request(), [
            'employer_id' => 'required',
            'title' => 'required',
            'description' => 'nullable',
            'estimated_budget' => 'required|numeric',
            'city' => 'nullable',
            'province' => 'nullable',
            'country' => 'nullable'
        ]);

        $job = Job::find($job_id);//get job

        //update job
        $job->title = $request->get('title');
        $job->description = $request->get('description');
        $job->estimated_budget = $request->get('estimated_budget');
        $job->province = $request->get('province');
        $job->country = $request->get('country');
        $job->city = $request->get('city');
        $job->save();

        // update job required skills
        // old | new | add | delete
        //  0  |  0  | no  | no  
        //  0  |  1  | yes | no
        //  1  |  0  | no  | yes
        //  1  |  1  | yes | yes
        $new_skill_ids = $request->get('new_skill_ids');
        $old_skill_ids = $request->get('old_skill_ids');

        if(sizeof($new_skill_ids) > 0 && sizeof($old_skill_ids) >0){
            $add_skills = array_diff($new_skill_ids, $old_skill_ids);
            $delete_skills = array_diff($old_skill_ids, $new_skill_ids);
        }else if(sizeof($new_skill_ids) > 0 && sizeof($old_skill_ids) == 0){
            $add_skills = $new_skill_ids;
            $delete_skills = [];
        }else if(sizeof($new_skill_ids) == 0 && sizeof($old_skill_ids) > 0){
            $add_skills = [];
            $delete_skills = $old_skill_ids;
        }else{
            $add_skills = [];
            $delete_skills = [];
        }

        // dd([$add_skills, $delete_skills]);
        foreach ($add_skills as $skill_id) {
            DB::select("insert into job_required_skill(job_id, skill_id) 
                values('".$job_id."', '".$skill_id."');");
        }

        foreach ($delete_skills as $skill_id) {
            DB::select("delete from job_required_skill where  
                job_id=".$job_id." and skill_id=".$skill_id.";");
        }

        return back()->with('message',['success', 'Job info has been updated!']);
    }


    //delete a job post if
    public function destroy($job_id)
    {
        //get job
        $job = Job::find($job_id);//get job

        //validate
        if($job->status == "open" && $job->employer_status == "posted"){
            //delete job post by change status
            $job->status = "delete";
            $job->employer_status = "cancelled";
            $job->save();

            return back()->with('message', ['success', 'Job '. $job->title .' has been deleted']);
        }else {
            return back()->with('message', ['danger', 'Job '. $job->title .' cannot be deleted']);
        }
    }

    //employer accept the job done
    public function employerAccept($job_id){
        // dd(Auth::user()->id);
        
        //get job
        $job = Job::find($job_id);//get job
        if(Auth::user()->id == $job->employer_id && $job->status == 'in-progress'){
            $job->status = "complete";
            $job->employer_status = "accepted";            
            $job->jobseeker_status = "done";
            $job->save();

            return back()->with('message', ['success', 'Job '. $job->title .' has been completed']);
        }else{
            return back()->with('message', ['danger', 'Job '. $job->title .' has not been completed']);
        }
    }

    //employer cancel the job
    public function employerCancel($job_id){
        //get job
        $job = Job::find($job_id);//get job

        if(Auth::user()->id == $job->employer_id && $job->status == 'in-progress'){
            $job->status = "incomplete";
            $job->employer_status = "cancelled";            
            $job->jobseeker_status = "working";
            $job->save();

            return back()->with('message', ['success', 'Job '. $job->title .' has been cancelled']);
        }else{
            return back()->with('message', ['danger', 'Job '. $job->title .' has not been cancelled']);
        }
    }

    // jobseeker cancel the job
    public function jobseekerCancel($job_id){
        //get job
        $job = Job::find($job_id);//get job
        
        if(Auth::user()->id == $job->jobseeker_id && $job->status == 'in-progress'){
            $job->status = "incomplete";
            $job->employer_status = "picked";            
            $job->jobseeker_status = "cancelled";
            $job->save();

            return back()->with('message', ['success', 'Job '. $job->title .' has been cancelled']);
        }else{
            return back()->with('message', ['danger', 'Job '. $job->title .' has not been cancelled']);
        }
    }

    //employer pick a jobseeker
    public function employerPick($job_id, Request $request){
        //get job
        $job = Job::find($job_id);//get job

        $job->jobseeker_id = request()->jobseeker_id;
        $job->status = "in-progress";
        $job->employer_status = "picked";            
        $job->jobseeker_status = "working";
        $job->save();

        return redirect('/home');
    }
}
