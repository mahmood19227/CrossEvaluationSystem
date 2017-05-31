<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cross Evaluation System</title>

</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
سامانه ارزیابی ارایه ها
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->

            @if(!Auth::guest())
                @if(Auth::user()->isAdmin())
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                ارایه ها
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/admin/presentations') }}">
                                        همه ارایه ها
                                    </a></li>
                                <li><a href="{{ url('/view_open_presentations') }}">
                                        ارایه های باز
                                    </a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                حضور و غیاب
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/admin/register_absence') }}">
                                        ثبت غیبت دانشجو
                                    </a></li>
                                <li><a href="{{ url('/admin/remove_illegal_evaluations') }}">
حذف ارزیابیهای غیرمجاز
                                    </a></li>
                            </ul>
                        </li>
                    </ul>
                @endif
            @endif
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/register_presentation') }}">
ثبت اطلاعات ارایه
                            </a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/view_open_presentations') }}">
ارایه های باز
                        </a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/standings') }}">
                            جدول امتیازات
                        </a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">ورود</a></li>
                        <li><a href="{{ url('/register') }}">ثبت نام</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>خروج</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('js/bootstrap.min.js') }}"></script>
    <link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url('css/bootstrap-rtl.min_.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url('css/dataTables.bootstrap.min.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url('css/styles.css') }}" crossorigin="anonymous">

</body>
</html>
