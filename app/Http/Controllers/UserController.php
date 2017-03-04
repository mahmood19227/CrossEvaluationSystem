<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
    public function registerPresentationForm(){
        return view('register_presentation');
    }

}
