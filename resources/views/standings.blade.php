@extends('layouts.app')

@section('content')
<?php
        $i=0;
        ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">رده بندی امتیازات</div>
                        <table id="table1" class="table table-stripped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="col-sm-1">
                                    رتیه
                                </th>
                                <th class="col-sm-4">
                                    نام
                                </th>
                                <th class="col-sm-3">
جمع امتیاز
                                </th>
                                <th class="col-sm-3">
معدل امتیاز
                                </th>
                                <th class="col-sm-3">
تعداد ارزیابی
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($points as $point)
                                    <tr>
                                        <td><?php
                                                echo ++$i;
                                            ?></td>
                                        <td>
                                            {{$point['username']}}
                                        </td>
                                        <td>
                                            {{$point['sum']}}
                                        </td>
                                        <td>
                                            {{$point['avg']}}
                                        </td>
                                        <td>
                                            {{$point['evalcount']/4}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection