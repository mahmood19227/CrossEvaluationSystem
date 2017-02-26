<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factor extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['userid', 'factorid', 'presentationid', 'point'];
}
