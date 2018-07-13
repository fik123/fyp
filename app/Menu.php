<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * The attributes that are mass assignable.
     * Setting mass array assginement
     * @var array
     */
    protected $fillable = [
    	'name','price','description','time_taken',
    ];
    	
}
