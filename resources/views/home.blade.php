@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">داشبورد</div>
                <div class="panel-body">
                    @if(!isset($message))
                    شما وارد سایت شده اید.
                    @else
                        {{ $message }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection