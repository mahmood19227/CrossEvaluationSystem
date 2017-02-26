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

    public function insert(Request $request){
		$name = $request['name'];
		$pass = md5($request['password']);
		$email = $request['email'];
		$created_at = time();
		$updated_at = time();
		DB::insert('insert into users(name,email,password,created_at,updated_at) values(?,?,?,?,?)',[$name,$email,$pass,$created_at,$updated_at]);
        echo "<br>User registered successfully.<br/>";
        echo '<a href = "/insert">Click Here</a> to go back.';
    }

    public function registerPresentationForm(){
        return view('register_presentation');
    }

}
