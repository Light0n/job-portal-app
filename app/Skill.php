<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table = 'skill';//table name is 'skill' (default is 'skills') 
    public $timestamps = false;// disable Laravel default $timestamps
    
    public function category(){
        return $this->belongsTo('App\Category', 'category_id', 'category_id');
    }
}
