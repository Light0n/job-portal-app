<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    protected $table = 'user_skill';//table name is 'user_skill' (default is 'user_skills')
    public $timestamps = false;// disable Laravel default $timestamps
}
