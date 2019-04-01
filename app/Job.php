<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'job';//table name is 'job' (default is 'jobs') 

    protected $fillable = ['employer_id', 'title', 'description',  'estimated_budget', 
        'city', 'province', 'country', 'status', 'employer_status',
        'jobseeker_id', 'jobseeker_status', 'employer_review_id', 'jobseeker_review_id'];
}
