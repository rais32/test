@extends('layouts.default')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">User</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    
    <div class="col-lg-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                List Coupon winners
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <div class="col-lg-12" style="padding-left:0;padding-bottom:20px;">
                        <form role="form" action="" role="form" method="GET">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Pencarian..." name="search">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                        </form>
                    </div>
                    @if(count($dataUsers) > 0)
                     
                    <table class="table table_top_ten">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Userame</th>
                                <th>Phone Number</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                
                            ?>
                            @foreach ($dataUsers as $dataUser)
                                <tr class="table_body">
                                    <td></td>
                                    <td>{{$dataUser->name}}</td>
                                    <td>{{$dataUser->phone_number}}</td>
                                    <td>{{date("h:i j-M-Y", strtotime($dataUser->created_at))}}</td>
                                    <td>
                                        <a href="{{url('send_to_user').'/'. $dataUser->id}}" class="btn btn-info">
                                            Send
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                
                            ?>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        <br/>
                        <h3 class="text-center">Tidak ada data</h3>
                    @endif
                <!-- /.table-responsive -->
                
                </div>
                <div class="clearfix">
                    @if(isset($queryString))
                        {!! $dataUsers->appends($queryString)->render() !!}
                    @else
                        {!! $dataUsers->render() !!}
                    @endif                
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
    <!-- hotwheel -->

    
</div>
    
@stop
@section('js_section')
    @parent
    <script>
        
    </script>
@stop