<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Evaluation;
use App\Factor;
use App\Presentation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function registerPresentationForm(){
        return view('register_presentation');
    }

    public function standings()
    {
        $points = [];
        $adminPoints =[]; //[presentationid,factorid]
        $evaluationsArray = []; //userid, factorid, presentationid
        $admin = User::where('usertype','=',1)->first();
        //print_r($admin);
        $evaluations = Evaluation::where('userid','!=',$admin->id)->get();
        $adminEvaluations = Evaluation::where('userid','=',$admin->id)->get();
        $users = User::where('usertype','==','0')->get();
        $factors = Factor::all();
        //var_dump($evaluations);
        $presentations = Evaluation::join('presentations','evaluations.presentationid','=','presentations.id')->select('evaluations.presentationid as id','presentations.userid as userid')->distinct()->get();
        $penalty = 200;
        $selfPoint = 0;
        //var_dump($presentations->toArray());

        foreach($users as $user) {
            foreach ($factors as $factor) {
                $points[$user->id][$factor->id] = 0;
                foreach($presentations as $presentation)
                    $evaluationsArray[$user->id][$factor->id][$presentation->id] = ($user->id==$presentation->userid)?$selfPoint:$penalty;
            }
            $points[$user->id]['evalcount'] = 0;
        }

        foreach($adminEvaluations as $eval)
        {
            $adminPoints[$eval->presentationid][$eval->factorid] = $eval->point;
        }

        foreach($evaluations as $eval)
        {
            $evaluationsArray[$eval->userid][$eval->factorid][$eval->presentationid] = $eval->point;
        }

        foreach($users as $user)
            foreach ($presentations as $presentation)
                foreach($factors as $factor)
                {
                    $dist = $evaluationsArray[$user->id][$factor->id][$presentation->id]-$adminPoints[$presentation->id][$factor->id];
                    $points[$user->id][$factor->id] += $dist*$dist;
                    //if()
                    $points[$user->id]['evalcount'] +=1;
                }

        $avgs = [];

        foreach($users as $user)
        {
            $points[$user->id]['sum'] = 0;
            foreach($factors as $factor) {
                if($points[$user->id][$factor->id])
                $points[$user->id]['sum'] += $points[$user->id][$factor->id];
            }
            $div = ($points[$user->id]['evalcount']?$points[$user->id]['evalcount']:1);
            $points[$user->id]['avg'] = sqrt($points[$user->id]['sum'] / $div ) ;
            $avgs[$user->id] = $points[$user->id]['avg'];
            $points[$user->id]['username'] = $user->name;
        }
        //var_dump($points);
        //var_dump($sums);
        array_multisort($avgs, SORT_ASC,$points);
        //var_dump($points);
        //print_r($users);
        return view('standings',['points'=>$points,'factors'=>$factor]);
        //*/
    }

}
