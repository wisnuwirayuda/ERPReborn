<!-- OLD -->
<!-- <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="height:35px;padding-top:14px;"> -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light flex justify-content-between" style="height:35px;">
    <ul class="navbar-nav" style="gap: 7px;">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="height: max-content;"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <span id="clock" style="vertical-align: middle; font-size: 0.85rem;"></span>
        </li>
    </ul>

    <!-- OLD -->
    <!-- <ul class="navbar-nav">
        <li class="nav-item">
            <br>
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <br>&nbsp;&nbsp;&nbsp;&nbsp;
            <span id="clock"></span>
        </li>
    </ul> -->

    <!-- OLD -->
    <!-- <ul class="navbar-nav ml-auto"> -->

    <ul class="navbar-nav">
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" style="height: max-content;">
                <img src="{{ asset('AdminLTE-master/dist/img/user.png')}}" class="user-image img-circle elevation-2" alt="User Image" style="width: 23px;height:23px;margin-top:1px;">
                <span class="d-none d-md-inline">{{Session::get('SessionLoginName')}}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <li class="user-header bg-secondary">
                    <img src="{{ asset('AdminLTE-master/dist/img/user.png')}}" class="img-circle elevation-2" alt="User Image">
                    @if(Session::get('SessionLoginName'))
                    <p>
                        {{Session::get('SessionLoginName')}}
                        <small><br> {{Session::get('SessionOrganizationalDepartmentName')}}</small>
                    </p>
                    @else
                    <p>
                        Admin
                        <small>{{Session::get('SessionCompanyName')}}</small>
                    </p>
                    @endif
                </li>

                <li class="user-footer">
                    <a href="#" class="btn btn-default btn-sm">Profile</a>
                    <a href="{{ route('logout') }}" class="btn btn-default btn-sm float-right">Sign out</a>
                </li>
            </ul>
        </li>
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" style="height: max-content;">
                <i class="fas fa-arrow-circle-down" style="position:relative;top:5px;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <li class="user-footer float-left">
                    <small>Mode</small>
                </li>

                <li class="user-footer float-right">
                    <select name="mode" id="mode" class="form-control">
                        <option selected>Select Mode</option>
                        <option value="dark">Dark</option>
                        <option value="light">Light</option>
                    </select>
                </li>
            </ul>
        </li>
    </ul>
</nav>