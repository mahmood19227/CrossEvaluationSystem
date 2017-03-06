@extends('layouts.adminPanel')
@section('content')
    <script>
    </script>
    <div class="container">
        @if($message!=null)
        <div class="row">
            {{ $message }}
        </div>
        @endif
        <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
            <table id="table1" class="table table-stripped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="col-sm-1">
                            ردیف
                        <th class="col-sm-5">
                            عنوان ارایه
                        <th class="col-sm-3">
                            ارایه دهنده
                        <th class="col-sm-3">
                            وضعیت ارزیابی
                        <th class="col-sm-1">
                        <th class="col-sm-1">
                        <th class="col-sm-1">

                    </tr>
                </thead>
                <?php
                    $i=0;
                    $now = str_replace('T',' ', date(DATE_ATOM));
                    $now = str_replace('+00:00','',$now);
                    foreach($presentations as $row)
                    {
                        if($row->userid==Auth::user()->id)
                            continue;
                        $i++;
                        echo '<tr>';
                        echo "<td>$i</td>";
                        echo "<td>$row->title</td>";
                        echo "<td>".$row->user->name."</td>";
                        echo "<td>$row->evaluation_start</td>";
                        echo "<td>$row->evaluation_end</td>";
                        echo "<td>$now</td>";
                        $status = "";
                        if($row->evaluation_start>$now)
                            $status = 'هنوز ارزیابی نشده';
                        elseif($row->evaluation_end<$now)
                            $status = 'ارزیابی شده';
                        else{
                            $status = "در حال ارزیابی<br/> <small>زمان باقی مانده:";
                            $remaining = strtotime( $row->evaluation_end )- time();
                            $status .= date("H:i:s",$remaining);
                        }
                        echo "<td>".$status."</td>";
                    ?>
                    <td>

                    </td>
                <td>
                        <a href="{{ url("/evaluate_presentation?id=").$row->id }}" class="btn btn-info" role=button"> ارزیابی</a>
                    </td>
                    <td>
                        <a href="{{ url("/admin/open_evaluation",['id'=>$row->id,'period'=>15,'offset'=>'now']) }}"
                        class="btn btn-info" role=button"><small>
                            باز کردن برای 15 دقیقه
                            </small></a></td>
                    <td>
                        <a href="{{ url("/admin/open_evaluation",['id'=>$row->id,'period'=>720,'offset'=>'now']) }}"
                           class="btn btn-info" role=button">
                            <small>
باز کردن برای 12 ساعت
                                </small></a>
                    </td>


                </tr>
                <?php
                } ?>
            </table>
        </div>
        </div>
    </div>
</div>
@endsection