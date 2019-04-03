<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Skill;

class SkillController extends Controller
{
    //show all skills
    public function index(){
        if(Auth::user()->type != 'admin')
            return redirect('/');
        
        $skills = DB::select("select s.id, s.skill_name, s.description, c.category_name from skill as s, category as c where s.category_id = c.id");

        return view("skill.index", compact("skills"));
    }

    public function destroy($skill_id)
    {
        if(Auth::user()->type != 'admin')
        return redirect('/');

        // delete all related in user_skill and job_required_skill tables
        DB::select('delete from user_skill where skill_id = '.$skill_id);
        DB::select('delete from job_required_skill where skill_id = '.$skill_id);
        
        //get skill name before delete
        $skill_name = DB::select("select skill_name from skill where id=".$skill_id);

        //delete skill
        DB::select("delete from skill where id=".$skill_id);
        
        return redirect('skill')->with('message', ['success', 'Skill '. ($skill_name? '"'.$skill_name[0]->skill_name.'"' : '') .' has been deleted']);
    }


    public function create(){
        if(Auth::user()->type != 'admin')
            return redirect('/');

        //get all categories
        $categories = DB::select('select id, category_name from category');

        return view("skill.create", compact('categories'));
    }

    public function store(Request $request){
        if(Auth::user()->type != 'admin')
            return redirect('/');

        //validate
        $skill = $this->validate(request(), [
            'category_id' => 'required',
            'skill_name' => 'bail|required',
            'description' => 'nullable'
          ]);
    
          Skill::create($skill);

          return back()->with('message', ['success', 'Skill "'.$skill["skill_name"]
          .'" has been created']);
    }

    public function edit($skill_id){
        if(Auth::user()->type != 'admin')
            return redirect('/');

        //get all categories
        $categories = DB::select('select id, category_name from category');

        $skill = Skill::find($skill_id);

        return view('skill.edit', compact('skill', 'skill_id', 'categories'));
    }

    public function update(Request $request, $skill_id){
        if(Auth::user()->type != 'admin')
            return redirect('/');
            
        $skill = Skill::find($skill_id);

        //validate
        $this->validate(request(), [
            'category_id' => 'required',
            'skill_name' => 'bail|required',
            'description' => 'nullable'
        ]);

        $skill->category_id = $request->get('category_id');
        $skill->skill_name = $request->get('skill_name');
        $skill->description = $request->get('description');
        $skill->save();
        return redirect('skill')->with('message',['success', 'Skill "'.$skill["skill_name"]
        .'" has been updated']);
    }
}
