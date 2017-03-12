@extends('layouts.app')
@section('content')
    <script>
    </script>
    <div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">لیست ارایه های باز</div>
                @if($open_presentation->count()==0)
                    <div class="panel-body">
                        در حال حاضر هیچ ارایه ای برای ارزیابی باز نیست
                    </div>
                @else
            <table id="table1" class="table table-stripped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="col-sm-1">
                            ردیف
                        </td>
                        <th class="col-sm-5">
                            عنوان ارایه
                        </td>
                        <th class="col-sm-3">
                            ارایه دهنده
                        </td>
                        <th class="col-sm-3">
                        </td>
                    </tr>
                </thead>
                <?php
                $i=0;
                foreach($open_presentation as $row)
                {
                    if($row->userid==Auth::user()->id)
                        continue;
                    $i++;
                    echo '<tr>';
                    echo "<td>$i</td>";
                    echo "<td>$row->title</td>";
                    echo "<td>".$row->user->name."</td>";?>

                    <td>
                        <a href="{{ url("/evaluate_presentation?id=").$row->id }}" class="btn btn-info" role=button"> ارزیابی</a>
                    </td>
                    </tr>
                <?php
                } ?>
            </table>
            @endif
        </div>
        </div>
    </div>
</div>
@endsection
