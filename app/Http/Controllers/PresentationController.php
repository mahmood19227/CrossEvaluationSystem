<?php

namespace App\Http\Controllers;

use App\Evaluation;
use App\Factor;
use App\Presentation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Eval_;

class PresentationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    function do_register(Request $request){
        $userid = Auth::user()->id;
        $presentation = Presentation::where('userid','=',$userid)->first();
        if(!$presentation) {
            $presentation = new Presentation();
            $presentation->userid = $userid;
        }
        $presentation->title = $request['title'];
        $presentation->presentation_date = $request['date'];
        $presentation->description = $request['desc'];
        $presentation->title = $request['title'];
        if($presentation->save())
            return view('home')->with('message','اطلاعات ارایه با موفقیت ثبت شد');
        else
            return view('home')->with('message','در ثبت ارایه مشکلی پیش آمده است');

    }

    function viewOpenPresentations(){
        $now = date(DATE_ATOM);
        $openPresentation = Presentation::whereDate('evaluation_start','<=',$now)
            ->whereDate('evaluation_end','>=',$now)
            ->get();
        echo $openPresentation->count();
        return view('openPresentations')->with('open_presentation',$openPresentation);
    }

    function evaluatePresentation(Request $request){
        $factors = Factor::all();
        $presentation = Presentation::find($request['id']);
        return view('evaluatePresentation',['factors'=>$factors,'presentation'=>$presentation]);
    }

    public function registerPresentationForm(){
        $userid = auth::user()->id;
        $user_presentation = Presentation::where('userid','=',$userid)->first();
        return view('register_presentation')
            ->with('user_presentation',$user_presentation);
    }
    public function registerEvaluations(Request $request){
        $now = date(DATE_ATOM);
        $presentation = Presentation::find($request['presentation_id']);
        if($presentation->evaluation_start>=$now or $presentation->evaluation_end<=$now)
            return view('home')->with('message','اکنون زمان ارزیابی این ارایه نیست');
        $userid = Auth::user()->id;
        $presentationdid = $presentation->id;
        $factorCount = Factor::all()->count();
        $saved = true;
        for($i=1;$i<=$factorCount;$i++)
        {
            $eval = Evaluation::firstOrNew(
                ['userid'=>$userid,'presentationid'=>$presentationdid,'factorid'=>$i]);
            $eval->point = $request[$i];
            $saved = $saved && $eval->save();
        }
        if($saved)
            return view('Home')->with('message','ارزیابی شما با موفقیت ثبت گردید');
        return view('Home')->with('message','مشکلی در ثبت ارزیابی شما پدید آمد!');

    }
}
