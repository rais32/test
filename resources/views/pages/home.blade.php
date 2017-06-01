@extends('layouts.default')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Leader Board</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    
    <div class="col-lg-4 col-md-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                Form User
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                    <div class="alert alert-danger fade in" style="display:none;">
                        <ul>
                            
                        </ul>
                    </div>
                    <div class="alert alert-success" style="display:none;">
            
                        <strong>Success</strong>
                    
                    </div>
                    <form id="form-user">
                        <div class="form-group">
                            <label>UUID</label>
                            <div><input type="text" name="uuid" value="{{$uuid}}" class="form-control" readonly></div>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <div><input type="text" name="name" class="form-control"></div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <div><input type="text" name="address" class="form-control"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Post</button> 
                    </form>
                    <span class="loading" style="display:none;">Loading...</span>
                
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
    <!-- hotwheel -->

    
    <!-- hotwheel -->
</div>

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                List Users
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">

                    @if(count($dataUsers) > 0)
                    <table id="table_user" class="table table_top_ten">
                        <thead>
                            <tr>
                                <th>UUID</th>
                                <th>Name</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataUsers as $dataUser)
                            <tr class="table_body">
                                <td>
                                    {{$dataUser->uuid}}
                                </td>
                                <td>
                                    {{$dataUser->nama}}
                                </td>                                    
                                <td>
                                    {{$dataUser->alamat}}
                                </td>
                            </tr>                            
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
</div>   
@stop
@section('js_section')
    @parent
    <script type="text/javascript">
        var btnPress = 1;
        
            $("#form-user").submit(function(){
                if(btnPress){
                    btnPress = 0;
                    $("div.form-group").removeClass("has-error");
                    $(".alert-danger").hide();    
                    $(".alert-success").hide();

                    $.ajax({
                        type: "POST",
                        url: "<?php echo url('post_add_user'); ?>",
                        data: $(this).serialize(),
                        dataType : "JSON",
                        success : function(data){
                            $('.btn-primary').prop('disabled', false);
                            $(".loading").hide();
                            btnPress = 1;

                            $(".alert-danger ul").html("");

                            if(data.t == 1){
                                $( ".alert-success" ).show().fadeOut( 5000);
                                htmlTr = '<tr><td>'+$('input[name="uuid"]').val()+'</td><td>'+$('input[name="name"]').val()+'</td><td>'+$('input[name="address"]').val()+'</td></tr>';
                                $('input[name="uuid"]').val(data.uuid);
                                $("#table_user tbody").append(htmlTr);
                            }   
                            else if(data.t == 0){
                                $(".alert-danger").show();
                                var htmlLi;

                                for(var index in data.errors) { 
                                    htmlLi = "<li>" + data.errors[index] + "</li>";
                                    $(".alert-danger ul").append(htmlLi); 
                                }
                                $( data.indexOfElement ).each(function(index, element) {
                                    $("div.form-group").eq(element).addClass("has-error");   
                                });

                            }     
                        }
                    });
                }
                
                return false;
            });
    </script>
@stop