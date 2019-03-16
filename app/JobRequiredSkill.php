<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobRequiredSkill extends Model
{
    protected $table = 'job_required_skill';//table name is 'job' (default is 'job_required_skills') 
    public $timestamps = false;// disable Laravel default $timestamps

}
