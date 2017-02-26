<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['userid', 'factorid', 'presentationid', 'point'];
}
