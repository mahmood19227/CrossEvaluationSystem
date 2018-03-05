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

    //In this standing people are penaltized respect to their absences
    public function standings()
    {
        //running queries
        $now = str_replace('T',' ', date(DATE_ATOM));
        $now = str_replace('+00:00','',$now);
        $openPresentation = Presentation::
            where('evaluation_start','<=',$now)
            ->where('evaluation_end','>=',$now)
            ->count();
        if($openPresentation>0 && !(Auth::user()!=null && Auth::user()->isAdmin())) {
            $message = 'در ساعاتی که نظرسنجی ارایه ها باز است امکان مشاهده رده بندی وجود ندارد';
            return view('home', ['message' => $message]);
        }

        //initializing
        $points = [];
        $adminEvaluationArray =[]; //[presentationid,factorid]
        $evaluationsArray = []; //userid, factorid, presentationid
        $admin = User::where('usertype','=',1)->first();
        $evaluations = Evaluation::where('userid','!=',$admin->id)->get();
        $adminEvaluations = Evaluation::where('userid','=',$admin->id)->get();
        $users = User::where('usertype','==','0')->get();
        $factors = Factor::all();
        $presentations = Evaluation::join('presentations','evaluations.presentationid','=','presentations.id')->select('evaluations.presentationid as id','presentations.userid as userid')->distinct()->get();
        $penalty = 100;
        $selfPoint = 0;

        foreach($adminEvaluations as $eval)
        {
            $adminEvaluationArray[$eval->presentationid][$eval->factorid] = $eval->point;
        }

        //put evaluations in evaluationArray
        foreach($evaluations as $eval)
        {
            $evaluationsArray[$eval->userid][$eval->factorid][$eval->presentationid] = $eval->point;
        }

        //calculate points array, by adding distance of each evaluationArrays entry with adminEvaluationArray
        $maxDist = 0;
        foreach($users as $user) {
            $points[$user->id]['evalcount'] = 0;
            foreach ($factors as $factor) {
                $points[$user->id][$factor->id] = 0;
            }
            foreach ($presentations as $presentation)
                foreach ($factors as $factor) {
                    if (isset($evaluationsArray[$user->id][$factor->id][$presentation->id])) {
                        $dist = $evaluationsArray[$user->id][$factor->id][$presentation->id] - $adminEvaluationArray[$presentation->id][$factor->id];
                        if($maxDist<$dist)
                            $maxDistUser = " userid = $user->id , $presentation->id ";
                        $maxDist = max($dist, $maxDist);
                        $points[$user->id][$factor->id] += $dist * $dist;
                        $points[$user->id]['evalcount'] += 1;
                    }
                }
        }

        //echo $maxDistUser;
        //echo "max distance is $maxDist";

        $avgs = [];

        $maxEvalCount = 1;
        foreach($points as $pointsRow) {
            $maxEvalCount = max($maxEvalCount, $pointsRow['evalcount']);
        }

        foreach($users as $user)
        {
            $points[$user->id]['sum'] = ($maxEvalCount-$points[$user->id]['evalcount'])*$maxDist*$maxDist;
            foreach($factors as $factor) {
                    $points[$user->id]['sum'] += $points[$user->id][$factor->id];
            }
            $points[$user->id]['avg'] = sqrt($points[$user->id]['sum'] / $maxEvalCount ) ;
            $avgs[$user->id] = $points[$user->id]['avg'];
            $points[$user->id]['username'] = $user->name;
        }
        array_multisort($avgs, SORT_ASC,$points);
        return view('standings',['points'=>$points,'factors'=>$factor]);
    }


    //In this standing people are penaltized respect to their absences, but diffrent penalty for each presentation and factor
    public function standings2()
    {
        //running queries
        $now = str_replace('T',' ', date(DATE_ATOM));
        $now = str_replace('+00:00','',$now);
        $openPresentation = Presentation::
        where('evaluation_start','<=',$now)
            ->where('evaluation_end','>=',$now)
            ->count();
        if($openPresentation>0 && !(Auth::user()!=null && Auth::user()->isAdmin())) {
            $message = 'در ساعاتی که نظرسنجی ارایه ها باز است امکان مشاهده رده بندی وجود ندارد';
            return view('home', ['message' => $message]);
        }

        //initializing
        $points = [];
        $adminEvaluationArray =[]; //[presentationid,factorid]
        $evaluationsArray = []; //userid, factorid, presentationid
        $admin = User::where('usertype','=',1)->first();
        $evaluations = Evaluation::where('userid','!=',$admin->id)->get();
        $adminEvaluations = Evaluation::where('userid','=',$admin->id)->get();
        $users = User::where('usertype','==','0')->get();
        $factors = Factor::all();
        $presentations = Evaluation::join('presentations','evaluations.presentationid','=','presentations.id')->select('evaluations.presentationid as id','presentations.userid as userid')->distinct()->get();
        $penalty = 100;
        $selfPoint = 0;

        foreach($adminEvaluations as $eval)
        {
            $adminEvaluationArray[$eval->presentationid][$eval->factorid] = $eval->point;
        }

        //put evaluations in evaluationArray
        foreach($evaluations as $eval)
        {
            $evaluationsArray[$eval->userid][$eval->factorid][$eval->presentationid] = $eval->point;
        }

        foreach ($users as $user) {
            $points[$user->id]['evalcount'] = 0;
            $points[$user->id]['sum'] = 0;
            foreach ($factors as $factor) {
                $points[$user->id][$factor->id] = 0;
            }
        }

        //calculate points array, by adding distance of each evaluationArrays entry with adminEvaluationArray
        $maxDist = [];
        foreach($presentations as $presentation) {
            foreach ($factors as $factor) {
                $maxDist = 0;
                foreach ($users as $user)
                    if (isset($evaluationsArray[$user->id][$factor->id][$presentation->id])) {
                        $dist = $evaluationsArray[$user->id][$factor->id][$presentation->id] - $adminEvaluationArray[$presentation->id][$factor->id];
                        $maxDist = max($dist, $maxDist);
                        $points[$user->id][$factor->id] += $dist * $dist;
                        $points[$user->id]['evalcount'] += 1;

                    }
                foreach ($users as $user)
                    if (!isset($evaluationsArray[$user->id][$factor->id][$presentation->id]) && $user->id!=$presentation->userid) {
                        $points[$user->id][$factor->id] += $maxDist * $maxDist;
                        if($user->id==29)
                            echo "$presentation->id  $maxDist*$maxDist";
                    }
            }
        }


        $avgs = [];
        $maxEvalCount = 0;
        foreach($points as $pointsRow) {
            $maxEvalCount = max($maxEvalCount, $pointsRow['evalcount']);
        }

        foreach($users as $user)
        {
            foreach($factors as $factor) {
                $points[$user->id]['sum'] += $points[$user->id][$factor->id];
            }
            $points[$user->id]['avg'] = sqrt($points[$user->id]['sum'] / max($points[$user->id]['evalcount'],1) ) ;
            $avgs[$user->id] = $points[$user->id]['avg'];
            $points[$user->id]['username'] = $user->name;
        }
        array_multisort($avgs, SORT_ASC,$points);
        return view('standings',['points'=>$points,'factors'=>$factor]);
    }

    //In this standing people aren't penaltized respect to their absences
    public function standings3()
    {
        //running queries
        $now = str_replace('T',' ', date(DATE_ATOM));
        $now = str_replace('+00:00','',$now);
        $openPresentation = Presentation::
        where('evaluation_start','<=',$now)
            ->where('evaluation_end','>=',$now)
            ->count();
        if($openPresentation>0 && !(Auth::user()!=null && Auth::user()->isAdmin())) {
            $message = 'در ساعاتی که نظرسنجی ارایه ها باز است امکان مشاهده رده بندی وجود ندارد';
            return view('home', ['message' => $message]);
        }

        //initializing
        $points = [];
        $adminEvaluationArray =[]; //[presentationid,factorid]
        $evaluationsArray = []; //userid, factorid, presentationid
        $admin = User::where('usertype','=',1)->first();
        $evaluations = Evaluation::where('userid','!=',$admin->id)->get();
        $adminEvaluations = Evaluation::where('userid','=',$admin->id)->get();
        $users = User::where('usertype','==','0')->get();
        $factors = Factor::all();
        $presentations = Evaluation::join('presentations','evaluations.presentationid','=','presentations.id')->select('evaluations.presentationid as id','presentations.userid as userid')->distinct()->get();
        $penalty = 100;
        $selfPoint = 0;

        foreach($adminEvaluations as $eval)
        {
            $adminEvaluationArray[$eval->presentationid][$eval->factorid] = $eval->point;
        }

        //put evaluations in evaluationArray
        foreach($evaluations as $eval)
        {
            $evaluationsArray[$eval->userid][$eval->factorid][$eval->presentationid] = $eval->point;
        }

        //calculate points array, by adding distance of each evaluationArrays entry with adminEvaluationArray
        $maxDist = 0;
        foreach($users as $user) {
            $points[$user->id]['evalcount'] = 0;
            foreach ($factors as $factor) {
                $points[$user->id][$factor->id] = 0;
            }
            foreach ($presentations as $presentation)
                foreach ($factors as $factor) {
                    if (isset($evaluationsArray[$user->id][$factor->id][$presentation->id])) {
                        $dist = $evaluationsArray[$user->id][$factor->id][$presentation->id] - $adminEvaluationArray[$presentation->id][$factor->id];
                        if($maxDist<$dist)
                            $maxDistUser = " userid = $user->id , $presentation->id ";
                        $maxDist = max($dist, $maxDist);
                        $points[$user->id][$factor->id] += $dist * $dist;
                        $points[$user->id]['evalcount'] += 1;
                    }
                }
        }

        echo $maxDistUser;
        echo "max distance is $maxDist";

        $avgs = [];

        $maxEvalCount = 0;
        foreach($points as $pointsRow) {
            $maxEvalCount = max($maxEvalCount, $pointsRow['evalcount']);
        }

        foreach($users as $user)
        {
            $points[$user->id]['sum'] = ($maxEvalCount-$points[$user->id]['evalcount'])*$maxDist*$maxDist;
            foreach($factors as $factor) {
                $points[$user->id]['sum'] += $points[$user->id][$factor->id];
            }
            $points[$user->id]['avg'] = sqrt($points[$user->id]['sum'] / $maxEvalCount ) ;
            $avgs[$user->id] = $points[$user->id]['avg'];
            $points[$user->id]['username'] = $user->name;
        }
        array_multisort($avgs, SORT_ASC,$points);
        return view('standings',['points'=>$points,'factors'=>$factor]);
    }

}
