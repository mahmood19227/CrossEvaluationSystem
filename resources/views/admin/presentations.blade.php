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
                        <th class="col-sm-2">
شروع ارزیابی
                        <th class="col-sm-2">
                            پایان ارزیابی
                        <th class="col-md-5">
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
                        echo "<td><small>$row->evaluation_start</small></td>";
                        echo "<td>$row->evaluation_end</td>";
                        $status = "";
                        if($row->evaluation_start>$now)
                            $status = 'هنوز ارزیابی نشده';
                        elseif($row->evaluation_end<$now){
                            $status = 'ارزیابی شده';
                            $status .= "<br/><small><a href=admin/view_evaluations/$row->id>";
                            $status .= "نتیجه ارزیابی";
                            $status .= "</a></small>";
                        }else{
                            $status = "در حال ارزیابی<br/> <small>زمان باقی مانده:";
                            $remaining = strtotime( $row->evaluation_end )- time();
                            $status .= date("H:i:s",$remaining);
                        }
                        echo "<td>".$status."</td>";
                    ?>
                <td>
                        <a href="{{ url("/evaluate_presentation?id=").$row->id }}" class="btn btn-info" role=button"> ارزیابی</a>
                    </td>
                <td>
                    <button type="button" class="btn btn-info" data-toggle="modal" onclick="openModal( {{$row->id}} );"><small>
                        باز کردن
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
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">باز کردن ارایه برای ارزیابی</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <label for="time" class="col-md-3 control-label">
                        مدت زمان باز کردن:
                    </label>
                    <input type="hidden" id="id" name="id" value="??">
                    <input type=number id="time" name="time" style="width:4em;">
                        <span   >
                            دقیقه
                        </span>

                </div>
                    <div class="row">
                    <label id="errorMessage" class="error-content"></label>
                </div>
                    <div class="row" style="padding-top: 1em;">
                        <div class="col-md-2">
                            <button type="button" onclick="submitModal();" class="btn btn-default" >باز کن</a>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn" data-dismiss="modal">انصراف</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row" style="padding-bottom: 1em">
                        <div class="col-md-3">
                            <a class="btn btn-info" onclick="submitModal(15);" role=button">
                                <small>
                                    باز کردن برای 15 دقیقه
                                </small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info" onclick="submitModal(720);" role=button">
                                <small>
                                    باز کردن برای 12 ساعت
                                </small>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <script>
        function openModal(id)
        {
            $("#id").val(id);
            $('#myModal').modal();
        }

        function submitModal(time)
        {
            if(time==null && $("#time").val()==""){
                $("#errorMessage").innerText = "مدت زمان تمدید را مشخص کنید";
                return;
            }
            if(time==null)
                time = $("#time").val();
            //this ces prefix must be corrected
            href = "/ces/admin/open_evaluation/" + $("#id").val() +"/"+ time + "/now";
            window.location.href = href;
        }
    </script>
@endsection