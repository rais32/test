@extends('layouts.default')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Leader Board</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                List Top 10 Winner Barbie
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    @if(count($topTenBarbie) > 0)
                    <table class="table table_top_ten">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Userame</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $x = 1;
                            ?>
                            @foreach ($topTenBarbie as $dataBarbie)
                                <tr class="table_body">
                                    <td>{{$x}}</td>
                                    <td>{{$dataBarbie->name}}</td>
                                    <td>{{$dataBarbie->barbie_score}}</td>
                                </tr>
                            <?php
                                $x++;
                            ?>
                            @endforeach
                            
                            
                        </tbody>
                    </table>
                    @else
                        <h3 class="text-center">Tidak ada data</h3>
                    @endif
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
    <!-- hotwheel -->

    <div class="col-lg-6 col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                List Top 10 Winner Hotwheel
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    @if(count($topTenHotwheel) > 0)
                    <table class="table table_top_ten">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Userame</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $x = 1;
                            ?>
                            @foreach ($topTenHotwheel as $dataHotwheel)
                                <tr class="table_body">
                                    <td>{{$x}}</td>
                                    <td>{{$dataHotwheel->name}}</td>
                                    <td>{{$dataHotwheel->hotwheel_score}}</td>
                                </tr>
                            <?php
                                $x++;
                            ?>
                            @endforeach
                            
                            
                        </tbody>
                    </table>
                    @else
                        <h3 class="text-center">Tidak ada data</h3>
                    @endif
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
    <!-- hotwheel -->
</div>

    
@stop
@section('js_section')
    @parent
@stop