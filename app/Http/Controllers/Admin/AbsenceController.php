<?php

namespace App\Http\Controllers\Admin;

use App\Absence;
use App\Presentation;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Evaluation;
use App\Factor;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsenceController extends \App\Http\Controllers\Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function absenceRegForm()
    {
        $users = User::where('usertype','!=','1')->get();
        $presentation = Presentation::all();
        return view('admin.register_absence',['users'=>$users,'presentations'=>$presentation]);
    }

    public function doRegisterAbsence(Request $request)
    {
        $userid = $request['user'];
        $saved = true;
        foreach($request['presentation'] as $p)
        {
            $ab = Absence::firstOrNew(['user_id'=>$userid,'presentation_id'=>$p]);
            $saved = $ab->save();
        }
        if($saved)
            $message = "غیبتها با موفقیت ذخیره شد";
        else
            $message = 'خطایی پیش آمده است';
        $users = User::where('usertype','!=','1')->get();
        $presentation = Presentation::all();
        return view('admin.register_absence',['users'=>$users,'presentations'=>$presentation, 'message'=>$message]);

    }

    public function removeIllegalEvaluations()
    {
        $absences = Absence::all();
        $deleted = 0;
        foreach($absences as $ab)
        {
            $deleted += Evaluation::where('userid',$ab->user_id)
                ->where('presentationid',$ab->presentation_id)
                ->delete();

        }
        if($deleted==0)
            $message = "هیچ ارزیابی غیر مجازی وجود نداشت";
        else
        {
            $deleted /=4;
            $message = "تعداد "  .$deleted." حذف با موفقیت انجام شد.";
        }
        return view('admin.home',['message'=>$message]);
    }
}
