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
        //echo $presentations->count();
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
            ->join('factors','factorid','=','factors.id');
        $users = $evaluations->select('users.name')->distinct()->get();
        $factors = $evaluations->select('factors.name')->distinct()->get();
        $points = $evaluations
            ->select('users.name as username','factors.name as factorname','evaluations.point')
            ->get();


        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = $user['name'];
        }
        foreach ($factors as $factor) {
            $factorsArray[] = $factor['name'];
        }
        //print_r($factorsArray);

        $evaluationsArray = [];

        $evaluationsArray[] = ['موضوع ارایه',$presentation->title];
        $evaluationsArray[] = ['ارایه دهنده',$presentation->user->name];
        $evaluationsArray[] = ['تاریخ ارایه',$presentation->presentationdate];
        $evaluationsArray[] = [];
            // Define the Excel spreadsheet headers
        $i = $users->count()+11;
        $evaluationsArray[] = ['', 'نمرات استاد','میانگین کلاس', '', 'نزدیکترین تقریب',"=MIN(E12:E$i)","=VLOOKUP(F5,E12:F$i,2,TRUE)"];
        $evaluationsArray[] = ['فن بیان', '', '=AVERAGE(A12:A100)', '', 'دورترین تقریب', "=MAX(E12:E$i)","=VLOOKUP(F6,E12:F$i,2,TRUE)"];
        $evaluationsArray[] = ['کیفیت مطلب', '', "=AVERAGE(B12:B$i)"];
        $evaluationsArray[] = ['کیفیت اسلایدها' , '', "=AVERAGE(C12:C$i)"];
        $evaluationsArray[] = ['زمان بندی' ,'', "=AVERAGE(D12:D$i)"];

        $evaluationsArray[] = [];


        foreach ($factorsArray as $factor) {
            $evaluationsArray[10][array_search($factor,$factorsArray)] = $factor;
        }
        $evaluationsArray[10][4] = 'فاصله';
        $evaluationsArray[10][5] = 'کاربر';

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        //echo $factorsArray;
        foreach ($points as $eval) {
            //$evaluationsArray[] = $eval->toArray();
            $row = array_search($eval->username,$usersArray)+11;
            $col = array_search($eval->factorname,$factorsArray);
            //$p = $eval->point;
            //echo "$row , $col => $p<br/>";
            $evaluationsArray[$row][$col] = $eval->point;
        }


        foreach ($usersArray as $user) {
            $row = array_search($user,$usersArray)+11;
            $row1 = $row+1;
            $evaluationsArray[$row][4] = "=SUMSQ(A$row1-B\$6,B$row1-B\$7,C$row1-B\$8,D$row1-B\$9)";
            $evaluationsArray[$row][5] = $user; //5 = nfactors+1
        }

        //print_r($evaluationsArray);
        //Generate and return the spreadsheet
        Excel::create($presentation->user->name, function($excel) use ($evaluationsArray) {

            // Set the spreadsheet title, creator, and description
            //$excel->setPreCalculateFormulas(false);
            $excel->setTitle('Evaluations');
            $excel->setCreator('CES')->setCompany('Mahmood Farokhian');
            $excel->setDescription('evaluations file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($evaluationsArray) {
                $sheet->fromArray($evaluationsArray, null, 'A1', false, false);
            });

        })->download('xlsx');
        //*/
    }
}