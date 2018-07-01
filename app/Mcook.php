<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mcook extends Model
{
    public function menu()
    {
        return $this->belongsTo('App\Menu');
    }
}
