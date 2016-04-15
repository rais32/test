<!doctype html>
<html>
<head>
    @include('includes.head')
    

    <link rel="stylesheet" href="{{ URL::asset('stylesheets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('stylesheets/css/font-awesome/css/font-awesome.min.css')}}" type="text/css">    
    <link rel="stylesheet" href="{{ URL::asset('stylesheets/css/jquery.mCustomScrollbar.css')}}">
    <!-- MetisMenu CSS -->
    <link href="{{ URL::asset('stylesheets/css/metisMenu.min.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ URL::asset('stylesheets/css/sb-admin-2.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('stylesheets/css/alpenliebe-style.css')}}">
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    @yield('css_section')
</head>
<body id="page-top">
<div id="wrapper">
@if (Auth::check())
    @include('includes.header')
    <div id="page-wrapper" style="min-height:800px">
        @yield('content')
    </div>
@else
    @yield('content')
@endif
<!-- /#page-wrapper --> 
@include('includes.footer')
</div>
<!-- /#wrapper -->    
<!-- jQuery -->
<script type="text/javascript" src="{{ URL::asset('stylesheets/js/jquery.js')}}"></script>
    
<!-- Bootstrap Core JavaScript -->
<script src="{{ URL::asset('stylesheets/js/bootstrap.min.js')}}"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="{{ URL::asset('stylesheets/js/metisMenu.min.js')}}"></script>

<!-- Custom Theme JavaScript -->
<script src="{{ URL::asset('stylesheets/js/sb-admin-2.js')}}"></script>
@yield('js_section')
</body>
</html>