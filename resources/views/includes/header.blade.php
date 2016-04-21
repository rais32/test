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
                    <a href="{{ url('/') }}">
                        <i class="fa fa-home"></i>&nbsp;
                        Home
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-list" aria-hidden="true"></i>&nbsp;
                        Coupons
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ url('list_winners') }}">
                                <i class="fa fa-star" aria-hidden="true"></i>&nbsp;
                                List Coupon Winners
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('add_coupon') }}">
                                <i class="fa fa-upload" aria-hidden="true"></i>&nbsp;
                                Add More Coupon
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('list_coupons') }}">
                                <i class="fa fa-list" aria-hidden="true"></i>&nbsp;
                                List Coupon
                            </a>
                        </li>                   
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="{{ url('options') }}">
                        <i class="fa fa-cog"></i>&nbsp;
                        Options
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-user" aria-hidden="true"></i>&nbsp;
                        Users
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ url('list_users') }}">
                                <i class="fa fa-users" aria-hidden="true"></i>&nbsp;
                                List User
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('send_to_all') }}">
                                <i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;
                                Send To All Users
                            </a>
                        </li>
                    </ul>
                    
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