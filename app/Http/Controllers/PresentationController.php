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
        $now = str_replace('T',' ', date(DATE_ATOM));
        $now = str_replace('+00:00','',$now);
        $openPresentation = Presentation::
            where('evaluation_start','<=',$now)
            ->where('evaluation_end','>=',$now)
            ->get();
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
        $now = str_replace('T',' ', date(DATE_ATOM));
        $now = str_replace('+00:00','',$now);
        $presentation = Presentation::find($request['presentation_id']);
        if($presentation->evaluation_start>=$now or $presentation->evaluation_end<=$now)
            return view('home')->with('message','اکنون زمان ارزیابی این ارایه نیست');
        $userid = Auth::user()->id;
        $presentationdid = $presentation->id;
        $factors = Factor::all();
        $saved = true;
       foreach($factors as $factor)
        {
            $fid = $factor->id;
            $eval = Evaluation::firstOrNew(
                ['userid'=>$userid,'presentationid'=>$presentationdid,'factorid'=>$fid]);
            $eval->point = $request["$fid"];
            $saved = $saved && $eval->save();
        }
        if($saved)
            return view('home')->with('message','ارزیابی شما با موفقیت ثبت گردید');
        return view('home')->with('message','مشکلی در ثبت ارزیابی شما پدید آمد!');

    }
}
