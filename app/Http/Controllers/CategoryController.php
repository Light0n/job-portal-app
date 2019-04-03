<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Category;

class CategoryController extends Controller
{
    //show all categories
    public function index(){
        if(Auth::user()->type != 'admin')
            return redirect('/');
        
        $categories = DB::select("select * from category");

        return view("category.index", compact("categories"));
    }

    public function destroy($category_id)
    {
        if(Auth::user()->type != 'admin')
            return redirect('/');

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
        if(Auth::user()->type != 'admin')
            return redirect('/');

        return view("category.create");
    }

    public function store(Request $request){
        if(Auth::user()->type != 'admin')
            return redirect('/');

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
        if(Auth::user()->type != 'admin')
            return redirect('/');

        $category = Category::find($category_id);
        // dd($category);
        return view('category.edit', compact('category', 'category_id'));
    }

    public function update(Request $request, $category_id){
        if(Auth::user()->type != 'admin')
            return redirect('/');

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
