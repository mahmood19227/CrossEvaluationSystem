@extends('layouts.app')
@section('content')
    <?php
    //echo $presentation;
    ?>

    <div class="container">
    <div class="row">
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    ارزیابی ارایه
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

                    <form class="form-horizontal" role="form" method="get" action="{{ url('/do_register_evaluations') }}">
                        <input type="hidden" name="presentation_id" value="{{$presentation->id}}">
                        @foreach($factors as $factor)
                        <div  class="form-group">
                            <label for="title" class="col-md-4 control-label">
                                {{ $factor->name }}
                            </label>
                            <div class="col-md-3">
                                <input id="{{ $factor->id }}" name="{{ $factor->id }}" type="number" class="form-control" placeholder="
                                بین {{ $factor->min }} و {{ $factor->max }}">

                                <!--@if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif!-->
                            </div>
                        </div>
                        @endforeach


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>
ثبت امتیازات
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
