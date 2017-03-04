@extends('layouts.adminPanel')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">پنل مدیریت</div>
                    <div class="panel-body">
                        @if(!isset($message))
خوش آمدید
                        @else
                            {{ $message }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection