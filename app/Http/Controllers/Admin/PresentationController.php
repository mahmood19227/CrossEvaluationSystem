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
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;


class PresentationController extends \App\Http\Controllers\PresentationController
{
    //
    function viewPresentations($message=null){
        $presentations = Presentation::all();
        echo $presentations->count();
        return view('admin.presentations')
            ->with('presentations',$presentations)
            ->with('message',$message);
    }

    function openEvaluation($id,$period,$offset){
        if($offset=='now'){
            $p = Presentation::find($id);
            $now = time();
            $p->evaluation_start = date(DATE_ATOM);
            $p->evaluation_end = date(DATE_ATOM,$now+$period*60);
            if($p->save())
                return $this->viewPresentations("ارایه مورد نظر به مدت $period دقیقه باز شد.");
            else
                return $this->viewPresentations('مشکلی پیش آمده است.');
        }
    }

    function viewEvaluations($id){
        $presentation = Presentation::find($id);
        $evaluations = $presentation->evaluations()->get();
        //echo $evaluations;
        $avgs = $presentation->evaluations()->groupBy('factorid')->selectRaw('*,avg(point) as avg')->get();
        //echo $avgs;
        return view('admin.evaluations',['presentation'=>$presentation,'evaluations'=>$evaluations]);
    }

    function exportEvaluations($id)
    {
        $presentation = Presentation::find($id);
        $evaluations = $presentation->evaluations()
            ->join('users','userid','=','users.id')
            ->join('factors','factorid','=','factors.id')
            ->select('evaluations.*','users.name as username','factors.name as factorname')
            ->get();

        $evaluationsArray = [];
        $evaluationsArray[] = ['موضوع ارایه',$presentation->title];
        $evaluationsArray[] = ['ارایه دهنده',$presentation->user->name];
        $evaluationsArray[] = ['تاریخ ارایه',$presentation->presentationdate];
        $evaluationsArray[] = [];
            // Define the Excel spreadsheet headers
        $evaluationsArray[] = ['id', 'presentationid','factorid','userid','point','username','factorname'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($evaluations as $eval) {
            $evaluationsArray[] = $eval->toArray();
        }

        // Generate and return the spreadsheet
        Excel::create('evaluations', function($excel) use ($evaluationsArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Evaluations');
            $excel->setCreator('CES')->setCompany('Mahmood Farokhian');
            $excel->setDescription('evaluations file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($evaluationsArray) {
                $sheet->fromArray($evaluationsArray, null, 'A1', false, false);
            });

        })->download('xlsx');
    }
}