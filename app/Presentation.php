<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    //
    public function user()
    {
        return $this->hasOne('App\User','id','userid');
    }

    public function evaluations()
    {
        return $this->hasMany('App\Evaluation','presentationid');
    }
}
