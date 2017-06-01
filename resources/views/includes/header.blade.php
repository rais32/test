<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    
    <div class="navbar-header" style="">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <!-- /.navbar-header -->

    
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a @if(Request::segment(1) == '') class="active" @endif href="{{ url('/') }}">
                        <i class="fa fa-home"></i>&nbsp;
                        Home
                    </a>
                </li>
                <li @if(Request::segment(1) == 'list_winners' || Request::segment(1) == 'list_coupons' || Request::segment(1) == 'add_coupon_barbie' || Request::segment(1) == 'add_coupon_hotwheel') class="active" @endif>
                    <a href="#">
                        <i class="fa fa-list" aria-hidden="true"></i>&nbsp;
                        Coupons 
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a @if(Request::segment(1) == 'list_winners') class="active" @endif href="{{ url('list_winners') }}">
                                <i class="fa fa-star" aria-hidden="true"></i>&nbsp;
                                List Coupon Winners
                            </a>
                        </li>
                        <li>
                            <a @if(Request::segment(1) == 'add_coupon_barbie') class="active" @endif href="{{ url('add_coupon_barbie') }}">
                                <i class="fa fa-upload" aria-hidden="true"></i>&nbsp;
                                Add More Coupon Barbie
                            </a>
                        </li>
                        <li>
                            <a @if(Request::segment(1) == 'add_coupon_hotwheel') class="active" @endif href="{{ url('add_coupon_hotwheel') }}">
                                <i class="fa fa-upload" aria-hidden="true"></i>&nbsp;
                                Add More Coupon Hotwheel
                            </a>
                        </li>
                        <li>
                            <a @if(Request::segment(1) == 'list_coupons') class="active" @endif href="{{ url('list_coupons') }}">
                                <i class="fa fa-list" aria-hidden="true"></i>&nbsp;
                                List Coupon
                            </a>
                        </li>                   
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a @if(Request::segment(1) == 'options') class="active" @endif href="{{ url('options') }}">
                        <i class="fa fa-cog"></i>&nbsp;
                        Options
                    </a>
                </li>
                <li @if(Request::segment(1) == 'list_users' || Request::segment(1) == 'send_to_all') class="active" @endif>
                    <a href="#">
                        <i class="fa fa-user" aria-hidden="true"></i>&nbsp;
                        Users
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a @if(Request::segment(1) == 'list_users') class="active" @endif href="{{ url('list_users') }}">
                                <i class="fa fa-users" aria-hidden="true"></i>&nbsp;
                                List User
                            </a>
                        </li>
                        <li>
                            <a @if(Request::segment(1) == 'send_to_all') class="active" @endif href="{{ url('send_to_all') }}">
                                <i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;
                                Send To All Users
                            </a>
                        </li>
                    </ul>
                    
                </li>
                <li>
                    <a @if(Request::segment(1) == 'change_password') class="active" @endif href="{{ url('change_password') }}">
                        <i class="fa fa-cog"></i>&nbsp;
                        Change Password
                    </a>
                </li>
                
                <li>
                    <a href="{{ url('/auth/logout') }}">
                        <i class="fa fa-power-off"></i>&nbsp;
                        Logout
                    </a>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>