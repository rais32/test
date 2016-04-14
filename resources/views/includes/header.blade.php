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
                <!--
                <li class="sidebar-search">
                    <form role="form" action="{{ url('/search') }}" role="form" method="GET">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Pencarian..." name="search">
                            <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        </div>
                    </form>
                    
                </li>
                -->

                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li>
                    <a href="#">Data Sekolah<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ url('data_sekolah/daftar_menu') }}">Menu</a>
                        </li>
                        <li>
                            <a href="{{ url('data_sekolah/daftar_kategori') }}">Kategori</a>
                        </li>
                        <li>
                            <a href="{{ url('data_sekolah/daftar_konten') }}">Konten</a>
                        </li>                        
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="{{ url('daftar_user') }}">Daftar User</a>
                </li>
                
                
                <li>
                    <a href="{{ url('/auth/logout') }}">Logout</a>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>