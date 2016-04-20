@extends('layouts.default')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Options</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                Total winners per day
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="alert alert-danger alert-danger-winner fade in" style="display:none;">
                    <ul>
                        
                    </ul>
                </div>
                <div class="alert alert-success alert-success-winner" style="display:none;">
                    <strong>Total pemenang berhasil diperbaharui</strong>
                </div>
                <form id="form-winner">
                    <div class="form-group">
                        <input type="number" value="{{$maxWinnerOptions[0]->value}}" name="max_winner" min="0">
                        &nbsp;
                        <button type="submit" class="btn btn-primary btn-primary-winner">Post</button>
                        <span class="loading-winner" style="display:none;">Loading...</span><br/>                        
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />
                    </div>
                    
                </form>
                
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                Probability Winner
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="alert alert-danger alert-danger-probability fade in" style="display:none;">
                    <ul>
                        
                    </ul>
                </div>
                <div class="alert alert-success alert-success-probability" style="display:none;">
                    <strong>Total pemenang berhasil diperbaharui</strong>
                </div>
                <form id="form-probability">
                    <div class="form-group">
                        <span>For minimum probability</span><br/>
                        <input type="number" value="{{$probMinOptions[0]->value}}" name="min_prob" min="0"><br/>
                        <span>For maximum probability</span><br/>
                        <input type="number" value="{{$probMaxOptions[0]->value}}" name="max_prob" min="0"><br/>
                        <button type="submit" class="btn btn-primary btn-primary-probability">Post</button>
                        <span class="loading-probability" style="display:none;">Loading...</span><br/>
                        <!--<span class="text-danger">Maximal total data 300</span>-->
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
</div>

    
@stop
@section('js_section')
    @parent

    <script type="text/javascript">
        $(document).ready(function(){
            var btnPressWinner = 1;
            $("#form-winner").submit(function(){
                if(btnPressWinner){
                    $('.btn-primary-winner').prop('disabled', true);
                    $(".loading-winner").show();
                    btnPressWinner = 0;
                    var formData = new FormData($("#form-winner")[0]);

                    $(".alert-danger-winner").hide();    
                    $(".alert-success-winner").hide();

                    $.ajax({
                        type: "POST",
                        url: "<?php echo url('update_total_winners'); ?>",
                        data: formData,
                        dataType : "JSON",
                        processData: false,
                        contentType: false,
                        success : function(data){
                            $('.btn-primary-winner').prop('disabled', false);
                            $(".loading-winner").hide();
                            btnPressWinner = 1;

                            $(".alert-danger-winner ul").html("");

                            if(data.t == 1){
                                $( ".alert-success-winner" ).show().fadeOut( 5000);
                            }   
                            else if(data.t == 0){
                                $(".alert-danger-winner").show();
                                var htmlLi;

                                for(var index in data.error_messages) { 
                                    htmlLi = "<li>" + data.error_messages[index] + "</li>";
                                    $(".alert-danger-winner ul").append(htmlLi); 
                                }
                                $( data.indexOfElement ).each(function(index, element) {
                                    $("div.form-winner").eq(element).addClass("has-error");   
                                });
                            }     
                        }
                    });
                }
                return false;
            });

            var btnPressProb = 1;
            $("#form-probability").submit(function(){
                if(btnPressProb){
                    $('.btn-primary-probability').prop('disabled', true);
                    $(".loading-probability").show();
                    btnPressProb = 0;
                    var formData = new FormData($("#form-probability")[0]);

                    $(".alert-danger-probability").hide();    
                    $(".alert-success-probability").hide();

                    $.ajax({
                        type: "POST",
                        url: "<?php echo url('update_probability'); ?>",
                        data: formData,
                        dataType : "JSON",
                        processData: false,
                        contentType: false,
                        success : function(data){
                            $('.btn-primary-probability').prop('disabled', false);
                            $(".loading-probability").hide();
                            btnPressProb = 1;

                            $(".alert-danger-probability ul").html("");
                            window.scrollTo(0, 0);

                            if(data.t == 1){
                                $( ".alert-success-probability" ).show().fadeOut( 8000);
                            }   
                            else if(data.t == 0){
                                $(".alert-danger-probability").show();
                                var htmlLi;

                                for(var index in data.error_messages) { 
                                    htmlLi = "<li>" + data.error_messages[index] + "</li>";
                                    $(".alert-danger-probability ul").append(htmlLi); 
                                }
                                $( data.indexOfElement ).each(function(index, element) {
                                    $("div.form-probability").eq(element).addClass("has-error");   
                                });
                            }     
                        }
                    });
                }
                return false;
            });
            

        });

    </script>
@stop