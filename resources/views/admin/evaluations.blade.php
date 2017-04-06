@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
نتیجه ارزیابی
                    </div>
                    <div class="panel-body">
                        <div class="row" style="height: 3em">
                            <div class="col-md-4 bold">
                                عنوان ارایه:
                            </div>
                            <div class="col-md-6">
                                {{ $presentation->title }}
                            </div>
                        </div>
                        <div class="row" style="height: 3em">
                            <div class="col-md-4 bold">
                                ارایه دهنده:
                            </div>
                            <div class="col-md-6">
                                {{ $presentation->user->name }}
                            </div>
                        </div>
                        <div class="row" style="height: 3em">
                            <div class="col-md-4 bold">
                                تاریخ ارایه
                            </div>
                            <div class="col-md-6">
                                {{ $presentation->presentation_date }}
                            </div>
                        </div>
                        <div class="row" style="height: 3em">
                            <div class="col-md-4 bold">
                                زمان پایان ارزیابی
                            </div>
                            <div class="col-md-6">
                                {{ $presentation->evaluation_end }}
                            </div>
                        </div>
                        <div class="row">
                            <table class="table">
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <div class="row">
                            <a class="btn btn-info" href="{{ route('admin.exportEvaluations' ,['id'=>$presentation->id]) }}">
                                تولید فایل اکسل
                            </a>

                        </div>
                        <div class="row">
                            <table class="table">
                                <thead>
                                <td>
ارزیابی کننده
                                </td>
                                <td>
                                    فاکتور ارزیابی
                                </td>
                                <td>
                                    نمره
                                </td>
                                </thead>

                                @foreach($evaluations as $eval)
                                    <tr>
                                        <td>
                                            {{$eval->user->name}}
                                        </td>
                                        <td>
                                            {{$eval->factor->name}}
                                        </td>
                                        <td>
                                            {{$eval->point}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection