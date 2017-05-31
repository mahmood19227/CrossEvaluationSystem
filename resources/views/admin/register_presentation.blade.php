@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
ثبت حضور و غیاب
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="get" action="{{ url('/do_register_presentation') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="title" class="col-md-4 control-label">
کاربران
                            </label>
                            <div class="col-md-6">
                                <select id="user" name="user" multiple>
                                    @foreach($users as $user)
                                        <option value="{{user->id}}">{{user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date" class="col-md-4 control-label">
ارایه ها
                            </label>
                            <div class="col-md-6">
                                <select id="user" name="user" multiple>
                                    @foreach($presentations as $presentation)
                                        <option value="{{$presentation->id}}">{{$presentation->title}}{{$presentation->presentation_date?" (".$presentation->presentation_date.")":""}}</option>
                                    @endforeach
                                </select>
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
