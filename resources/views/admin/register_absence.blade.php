@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        {{isset($message)?$message:''}}
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
ثبت غیبت دانشجو
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" action="{{ url('/admin/register_absence') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="user" class="col-md-4 control-label">
نام دانشجو
                            </label>
                            <div class="col-md-6">
                                <select id="user" class="form-control" name="user">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="presentation" class="col-md-4 control-label">
                                ارایه مربوطه
                            </label>
                            <div class="col-md-8">
                                <select id="presentation" class="form-control" name="presentation[]" multiple size="10">
                                    @foreach($presentations as $presentation)
                                        <option value="{{$presentation->id}}">{{$presentation->title}} - {{$presentation->user->name}} ({{$presentation->presentation_date}})</option>
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
