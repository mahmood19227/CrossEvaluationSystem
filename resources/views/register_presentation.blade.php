@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    ثبت ارایه
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="get" action="{{ url('/do_register_presentation') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">
                                عنوان ارایه
                            </label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ isset($user_presentation)?$user_presentation->title:"" }}">

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                            <label for="date" class="col-md-4 control-label">
تاریخ ارایه
                            </label>
                            <div class="col-md-6">
                                <input id="date" type="text" class="form-control" name="date" value="{{ isset($user_presentation)?$user_presentation->presentation_date:"" }}">

                                @if ($errors->has('date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('user') ? ' has-error' : '' }}">
                            <label for="user" class="col-md-4 control-label">
ارایه دهنده
                            </label>

                            <div class="col-md-6">
                                <input id="user" type="text" class="form-control" name="user" value="{{ Auth::user()->name }}" {{ Auth::user()->usertype==0?"disabled":""}}>

                                @if ($errors->has('user'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('desc') ? ' has-error' : '' }}">
                            <label for="desc" class="col-md-4 control-label">
                                توضیحات
                            </label>

                            <div class="col-md-6">
                                <input id="desc" type="text" class="form-control" name="desc" value="{{ isset($user_presentation)?$user_presentation->description:"" }}">

                                @if ($errors->has('desc'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('desc') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>
ثبت اطلاعات
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
