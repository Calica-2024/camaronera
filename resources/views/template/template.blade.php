<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Calica | Control {{ $modulo ? ' - ' . $modulo : ''  }}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset("/public/plugins/fontawesome-free/css/all.min.css") }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bbootstrap 4 -->
        <link rel="stylesheet" href="{{ asset("/public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset("/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css") }}">
        <!-- JQVMap -->
        <link rel="stylesheet" href="{{ asset("/public/plugins/jqvmap/jqvmap.min.css") }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset("/public/dist/css/adminlte.min.css") }}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ asset("/public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css") }}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ asset("/public/plugins/daterangepicker/daterangepicker.css") }}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{ asset("/public/plugins/summernote/summernote-bs4.css") }}">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <script src="{{ asset("/public/plugins/jquery/jquery.min.js") }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset("/public/plugins/jquery-ui/jquery-ui.min.js") }}"></script>
        <script src="{{ asset("justgage/justgage.js") }}"></script>
        <script src="{{ asset("justgage/raphael-2.1.4.min.js") }}"></script>
        @include('template.modals')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <!-- Messages Dropdown Menu -->
                    
                    <li class="nav-item">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                        <i class="fas fa-power-off"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="{{ url('/dashboard') }}" class="brand-link">
                    <img src="{{ asset("/public/dist/img/AdminLTELogo.png") }}" alt="Calica Control" class="brand-image img-circle elevation-3"
                        style="opacity: .8">
                    <span class="brand-text font-weight-light">CalicaControl</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="{{ asset("/public/dist/img/user_profile.png") }}" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                            with font-awesome or any other icon font library -->
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link {{ $modulo == 'Dashboard' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item {{ $grupo == 'camaroneras' ? 'has-treeview menu-open' : '' }}">
                                <a href="#" class="nav-link {{ $grupo == 'camaroneras' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-table"></i>
                                    <p>
                                        Camaroneras
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('camaroneras') }}" class="nav-link {{ $modulo == 'Camaroneras' ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Camaroneras</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item {{ $grupo == 'balanceados' ? 'has-treeview menu-open' : '' }}">
                                <a href="#" class="nav-link {{ $grupo == 'balanceados' ? 'active' : '' }}">
                                    <i class="fas fa-cookie"></i>
                                    <p>
                                        Balanceados
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('balanceados') }}" class="nav-link {{ $modulo == 'Balanceados' ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Balanceados</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item {{ $grupo == 'producciones' ? 'has-treeview menu-open' : '' }}">
                                <a href="#" class="nav-link {{ $grupo == 'producciones' ? 'active' : '' }}">
                                    <i class="fas fa-industry"></i>
                                    <p>
                                        Producciones
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('producciones') }}" class="nav-link {{ $modulo == 'Producciones' ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Producciones</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @yield('contenido')
            </div>

        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset("/public/plugins/bootstrap/js/bootstrap.bundle.min.js") }}">></script>
        <!-- ChartJS -->
        <script src="{{ asset("/public/plugins/chart.js/Chart.min.js") }}">></script>
        <!-- Sparkline -->
        <script src="{{ asset("/public/plugins/sparklines/sparkline.js") }}">></script>
        <!-- JQVMap -->
        <script src="{{ asset("/public/plugins/jqvmap/jquery.vmap.min.js") }}">></script>
        <script src="{{ asset("/public/plugins/jqvmap/maps/jquery.vmap.usa.js") }}">></script>
        <!-- jQuery Knob Chart -->
        <script src="{{ asset("/public/plugins/jquery-knob/jquery.knob.min.js") }}">></script>
        <!-- daterangepicker -->
        <script src="{{ asset("/public/plugins/moment/moment.min.js") }}">></script>
        <script src="{{ asset("/public/plugins/daterangepicker/daterangepicker.js") }}">></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{ asset("/public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js") }}">></script>
        <!-- Summernote -->
        <script src="{{ asset("/public/plugins/summernote/summernote-bs4.min.js") }}">></script>
        <!-- overlayScrollbars -->
        <script src="{{ asset("/public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js") }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset("/public/dist/js/adminlte.js") }}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        {{-- <script src="{{ asset("/public/dist/js/pages/dashboard.js") }}"></script> --}}
        <!-- AdminLTE for demo purposes -->
        <script src="{{ asset("/public/dist/js/demo.js") }}"></script>

        <script>
            function decimales(input) {
                // Elimina todos los caracteres que no sean números o puntos
                input.value = input.value.replace(/[^0-9.]/g, '');
    
                // Separa el valor en la parte entera y la parte decimal
                const partes = input.value.split('.');
                
                // Si hay más de un punto, elimina los puntos adicionales
                if (partes.length > 2) {
                    input.value = partes[0] + '.' + partes.slice(1).join('');
                }
    
                // Limita la parte decimal a 2 dígitos
                if (partes[1] && partes[1].length > 2) {
                    partes[1] = partes[1].substring(0, 2);
                    input.value = partes[0] + '.' + partes[1];
                }
            }
        </script>
    </body>
</html>
