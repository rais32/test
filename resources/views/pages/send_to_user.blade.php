@extends('layouts.default')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Send Message To {{$dataUser[0]->name}}</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    
    <div class="col-lg-6 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Form send message
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="alert alert-danger fade in" style="display:none;">
                    <ul>
                        
                    </ul>
                </div>
                <div class="alert alert-success" style="display:none;">
        
                    <strong>Berhasil mengirim pesan</strong>
                
                </div>
                <form id="form-coupon">
                    <div class="form-group">
                        <input type="text" name="message" placeholder="Message" style="width:250px;"><br/>
                        <span class="text-danger">Maximal 100 karakter</span><br/>
                        <!--<span class="text-danger">Maximal total data 300</span>-->
                        <input type="hidden" name="id" value="{{$dataUser[0]->id}}" />
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />
                    </div>
                    <button type="submit" class="btn btn-primary">Post</button> 
                    <span class="loading" style="display:none;">Loading...</span>
                </form>
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
    <script>
        $(document).ready(function(){
            var btnPress = 1;
            $("#form-coupon").submit(function(){
                if(btnPress){
                    $('.btn-primary').prop('disabled', true);
                    $(".loading").show();
                    btnPress = 0;

                    var formData = new FormData($("#form-coupon")[0]);

                    $("div.form-group").removeClass("has-error");
                    $(".alert-danger").hide();    
                    $(".alert-success").hide();

                    $.ajax({
                        type: "POST",
                        url: "<?php echo url('post_send_user'); ?>",
                        data: formData,
                        dataType : "JSON",
                        processData: false,
                        contentType: false,
                        success : function(data){
                            $('.btn-primary').prop('disabled', false);
                            $(".loading").hide();
                            btnPress = 1;

                            $(".alert-danger ul").html("");

                            if(data.t == 1){
                                $( ".alert-success" ).show().fadeOut( 5000,function(){
                                    //window.location = "<?php echo url('list_coupons'); ?>";
                                });
                            }   
                            else if(data.t == 0){
                                $(".alert-danger").show();
                                var htmlLi;

                                for(var index in data.error_messages) { 
                                    htmlLi = "<li>" + data.error_messages[index] + "</li>";
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
        });
    </script>
@stop