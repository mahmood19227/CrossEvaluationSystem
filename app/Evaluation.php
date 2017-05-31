<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    //
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['userid', 'factorid', 'presentationid', 'point'];
    protected $dates = ['deleted_at'];

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
