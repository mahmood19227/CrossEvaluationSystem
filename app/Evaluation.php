<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['userid', 'factorid', 'presentationid', 'point'];

    function user(){
        return $this->hasOne('App\User','id','userid');
    }

    function presentation()
    {
        return $this->hasOne('App\Presentation', 'id', 'presentationid');
    }

    function factor()
    {
        return $this->hasOne('App\Factor', 'id', 'factorid');
    }
}
