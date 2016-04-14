@extends('layouts.default')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">
                        Dashboard Alpenliebe
                    </h3>
                </div>
                <div class="panel-body">
                	@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
                    <form role="form" action="{{ url('/auth/login') }}" role="form" method="POST">
                    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <fieldset>
                            <div class="form-group">
                            <label>Email</label>
                                <input class="form-control" placeholder="Email" name="email" type="text" autofocus>
                            </div>
                            <div class="form-group">
                            <label>Password</label>
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>
                            {{--<div class="checkbox">
                                <label>
                                    <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                </label>
                            </div>--}}
                            <!-- Change this to a button or input when using this as a form -->
                            <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#wrapper -->   
@stop
@section('js_section')
    @parent
@stop