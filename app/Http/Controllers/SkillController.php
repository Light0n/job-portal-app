<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Skill;

class SkillController extends Controller
{
    //show all skills
    public function index(){
        
        $skills = DB::select("select * from skill");

        return view("skill.index", compact("skills"));
    }

    public function destroy($category_id)
    {
        //check number of skill belong to this category
        $count = DB::select("select count(*) as count from skill, category where category_id=category.id and category.id=".$category_id)[0]->count;
        
        $category_name = DB::select("select category_name from category where id=".$category_id);

        if($count == 0){//delete category
            DB::select("delete from category where id=".$category_id);
            return redirect('category')->with('message', ['success', 'Category '. ($category_name? '"'.$category_name[0]->category_name.'"' : '') .' has been deleted']);
        }else{//do not allow
            return redirect('category')->with('message',  ['danger','Category '.($category_name? '"'.$category_name[0]->category_name.'"' : '').' has not been deleted because it may have many dependent skills!']);
        }
    }

    public function create(){
        return view("category.create");
    }

    public function store(Request $request){
        //validate
        $category = $this->validate(request(), [
            'category_name' => 'bail|required|unique:category',
            'description' => 'nullable'
          ]);

          Category::create($category);

          return back()->with('message', ['success', 'Category "'.$category["category_name"]
          .'" has been created']);
    }

    public function edit($category_id){
        $category = Category::find($category_id);
        // dd($category);
        return view('category.edit', compact('category', 'category_id'));
    }

    public function update(Request $request, $category_id){
        $category = Category::find($category_id);

        if($request->get('category_name') !=  $category->category_name){
            // category name is diffirent, then check it is unique or not
            $this->validate(request(), [
                'category_name' => 'bail|required|unique:category',
                'description' => 'nullable'
            ]);
        }

        $category->category_name = $request->get('category_name');
        $category->description = $request->get('description');
        $category->save();
        return redirect('category')->with('message',['success', 'Category "'.$category["category_name"]
        .'" has been updated']);
    }
}
