<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

class UserController extends Controller
{
    public function show($user_id){
        // $result = [];
        // //get user
        $user = DB::select('select * from user where id = ?', [$user_id]);

        if($user)
            $user = $user[0];
        else
            return;

        //hide attributes
        unset($user->password);

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

        dd($user);

        return view("user", compact("user"));
    }

    // edit user info and skills
    public function edit($user_id){
        //get all skills and their categories
        $skills = DB::select("select s.id, s.skill_name, s.description, c.category_name from skill as s, category as c where s.category_id = c.id");

        $user = User::find($user_id);//get user
        //get current user skills
        $user_skills = DB::select("select * from user_skill where user_id =".$user_id);

        //if set selected property of all skills array based on current user_skills
        foreach ($skills as &$skill) {
            $skill->selected = 0;
            for($i=0; $i < sizeof($user_skills); $i++){
                if($skill->id == $user_skills[$i]->skill_id){
                    $skill->selected = 1;
                    break;
                }
            }
        }

        // dd($skills);

        return view('user.edit', compact('skills', 'user', 'user_id'));
    }

    public function update(Request $request, $user_id){
        //validate
        $this->validate(request(), [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'city' => 'string|max:255',
            'province' => 'string|max:255',
            'country' => 'string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        $user = User::find($user_id);//get user

        //update user
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->city = $request->get('city');
        $user->province = $request->get('province');
        $user->country = $request->get('country');
        $user->phone = $request->get('phone');
        $user->save();

        // update user skills
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
            DB::select("insert into user_skill(user_id, skill_id) 
                values('".$user_id."', '".$skill_id."');");
        }

        foreach ($delete_skills as $skill_id) {
            DB::select("delete from user_skill where  
                user_id=".$user_id." and skill_id=".$skill_id.";");
        }

        return back()->with('message',['success', 'User info has been updated!']);
    }
}
