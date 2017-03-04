<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Evaluation;
use App\Factor;
use App\Presentation;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Eval_;

class PresentationController extends \App\Http\Controllers\PresentationController
{
    //
    function viewPresentations($message=null){
        $presentations = Presentation::all();
        return view('admin.presentations')
            ->with('presentations',$presentations)
            ->with('message',$message);
    }

    function openEvaluation($id,$period,$offset){
        if($offset=='now'){
            $p = Presentation::find($id);
            $now = time();
            $p->evaluation_start = date(DATE_ATOM);
            $p->evaluation_end = date(DATE_ATOM,$now+15*60);
            if($p->save())
                return $this->viewPresentations('ارایه مورد نظر به مدت 15 دقیقه باز شد.');
            else
                return $this->viewPresentations('مشکلی پیش آمده است.');
        }
    }
}
