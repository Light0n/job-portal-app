<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';//table name is 'category' (default is 'categories') 

    public $timestamps = false;// disable Laravel default $timestamps

    public function skills(){
        return $this->hasMany('App\Skill', 'category_id', 'category_id');
    }
}
