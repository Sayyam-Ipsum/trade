<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Digital Farmers Market">
    <meta name="author" content="Digital Farmers Market">
    <meta name="generator" content="">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link href="/assets/admin/dist/img/AdminLTELogo.png" rel="apple-touch-icon-precomposed">
    <link href="/assets/admin/dist/img/AdminLTELogo.png" rel="shortcut icon" type="image/png">
    <title>@yield('page-title') | Easy Trade</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Sweet alert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Datatable CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Dropzone CSS -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css"
        type="text/css"
    />
    <style>
        label.error {
            color: #F00;
            font-weight: normal !important;
        }

        .field {
            margin-top: 15px;
        }

        .required:after{
            content:"*"!important;
            color: red!important;
        }
        .dz-image img {
            width: 100%;
            height: 100%;
        }
        .dropzone.dz-started .dz-message {
            display: block !important;
        }
        .dropzone {
            border: 2px dashed #028AF4 !important;;
        }
        .dropzone .dz-preview.dz-complete .dz-success-mark {
            opacity: 1;
        }
        .dropzone .dz-preview.dz-error .dz-success-mark {
            opacity: 0;
        }
        .dropzone .dz-preview .dz-error-message{
            top: 144px;
        }
        .dimmer {
            display: none;
            background: #000;
            opacity: 0.75;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99999;
        }
        .spinner-border{
            width: 5rem;
            height: 5rem;
            margin-top: 50vh;
            color: #5cb85c;

        }
    </style>
    @yield('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- dimmer starts -->
<div class="dimmer">
    <div class="text-center">
        <div class="spinner-border"
             role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="mt-3 " style=" color: #ffffff;">
            <h4> Loading... </h4>
        </div>
    </div>
</div>
<!-- dimmer ends -->
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__pulse" src="{{asset('assets/admin/dist/img/preloader.gif')}}" alt="Easy Trade Logo" >
    </div>

<!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="/" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-info elevation-4">
        <!-- Brand Logo -->
        <a href="{{'/'}}" class="brand-link text-center">
            <span class="brand-text font-weight-bold">Easy Trade</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    @php
                        $url = auth()->user()->photo ? auth()->user()->photo : asset('assets/site/img/user.png')
                    @endphp
                    <img src="{{$url}}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a class="d-block">{{auth()->user()->name}}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{url('admin/dashboard')}}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{url('admin/users')}}" class="nav-link ">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{url('admin/deposits')}}" class="nav-link ">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Deposits
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{url('admin/withdrawals')}}" class="nav-link ">
                            <i class="nav-icon fas fa-funnel-dollar"></i>
                            <p>
                                Withdrawals
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{url('admin/payment-methods')}}" class="nav-link">
                            <i class="nav-icon fab fa-usps"></i>
                            <p>
                                Payment Methods
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{url('admin/roles')}}" class="nav-link">
                            <i class="nav-icon fa fa-user-tag"></i>
                            <p>
                                Roles
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{url('admin/system-users')}}" class="nav-link">
                            <i class="nav-icon fa fa-user-shield"></i>
                            <p>
                                System Users
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{url('admin/profile')}}" class="nav-link ">
                            <i class="nav-icon fas fa-user-circle"></i>
                            <p>
                                Profile
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{url('admin/settings')}}" class="nav-link ">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Settings
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{url('logout')}}" class="nav-link bg-dark">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <div class="content-header shadow-sm">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="m-0">@yield('title')</h3>
                    </div>
                    <div class="col-sm-6 text-right">
                        @yield('page-actions')
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid py-2">
                @yield('content')
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{date('Y')}}</strong>
        All rights reserved.
        {{--        <div class="float-right d-none d-sm-inline-block">--}}
        {{--            <b>Version</b> 3.2.0--}}
        {{--        </div>--}}
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Modal Box -->
<div class="modal fade" id="global-modal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="global-modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="global-modal-body">
                Something went wrong
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('assets/admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Jquery Validation -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<!-- Dropzone JS -->
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<!-- ChartJS -->
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/dist/js/adminlte.js')}}"></script>
<script src="{{asset('assets/admin/plugins/moment/moment.min.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{--<script src="{{asset('assets/admin/dist/js/pages/dashboard.js')}}"></script>--}}
<!-- Sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<!-- Select2 -->
<script src="{{asset('assets/admin/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- Datatables JS -->
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>

    $(function(){
        var current = location.pathname;
        $('.nav .nav-item .nav-link').each(function(){
            var $this = $(this);
            // if the current path is like this link, make it active
            if($this.attr('href').indexOf(current) != -1){
                $this.addClass('active');
            }
        });
    });
</script>
<script>
    //Initialize Select2 Elements
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    function alert(message, flag){
        if(flag === 'success'){
            runAlert('Success', message, flag);
        }
        if(flag === 'error'){
            runAlert('Error', message, flag);
        }
        if(flag === 'info'){
            runAlert('Info', message, flag);
        }
        if(flag == 'warning'){
            runAlert('Warning', message, flag);
        }
    }

    function toast(title, type) {
        Swal.fire({
            toast: true,
            title: title,
            text: '',
            type: type,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            // timerProgressBar: true,
        });
    }

    function runAlert(title, message, flag){
        Swal.fire({
            title: title,
            text: message,
            type: flag,
            showConfirmButton: false,
            position: 'center',
            timer: 1000
        });
    }

    function open_modal(url) {
        $.ajax({
            url: url,
            type: "GET",
            data: {},
            dataType: "json",
            cache: false,
            success: function(res) {
                $("#global-modal-title").html(res.title);
                $("#global-modal-body").html(res.html);
                if ($("#global-modal-body").find('.select2')) {
                    $("#global-modal-body").find('.select2').select2({
                        theme: "bootstrap4",
                        dropdownParent: $('#global-modal')
                    });
                }
                $("#global-modal").modal("show");
            }
        });
    }
</script>
<script type="text/javascript">
    (function ($) {
        /* Store sidebar state */
        $('.sidebar-toggle').click(function(event) {
            event.preventDefault();
            if (Boolean(localStorage.getItem('sidebar-toggle-collapsed'))) {
                localStorage.setItem('sidebar-toggle-collapsed', '');
            } else {
                localStorage.setItem('sidebar-toggle-collapsed', '1');
            }
        });
    })(jQuery);
</script>
<script type="text/javascript">
    /* Recover sidebar state */
    (function () {
        if (Boolean(localStorage.getItem('sidebar-toggle-collapsed'))) {
            var body = document.getElementsByTagName('body')[0];
            body.className = body.className + ' sidebar-collapse';
        }
    })();
</script>
<script>
    @include('partials.response')
</script>
@yield('scripts');
</body>
</html>
