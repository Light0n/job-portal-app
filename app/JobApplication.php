<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $table = 'job_application';//table name is 'job_application' (default is 'job_applications') 
    public $timestamps = false;// disable Laravel default $timestamps
}
