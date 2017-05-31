<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    public $timestamps = false;
    public $fillable = ['user_id','presentation_id'];

    function user(){
        return $this->hasOne('App\User','id','user_id');
    }

    function presentation()
    {
        return $this->hasOne('App\Presentation', 'id', 'presentation_id');
    }

}
