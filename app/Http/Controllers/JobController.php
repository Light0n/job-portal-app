<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function index(){
        //show job that have status 'open'
        $jobs = DB::select("select * from job where status='open'");
        return view("jobs", compact("jobs"));
        // return dd(DB::select("select * from job where status='open'"));
    }
}
